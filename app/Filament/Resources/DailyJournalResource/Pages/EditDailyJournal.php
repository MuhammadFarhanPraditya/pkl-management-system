<?php

namespace App\Filament\Resources\DailyJournalResource\Pages;

use App\Filament\Resources\DailyJournalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyJournal extends EditRecord
{
    protected static string $resource = DailyJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn ($record) => ! auth()->user()?->isStudent() || $record->status !== 'approved'),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);

        $user = auth()->user();

        if ($user?->isTeacher()) {
            \Filament\Notifications\Notification::make()
                ->title('Akses Ditolak')
                ->body('Guru tidak diizinkan mengubah isi jurnal siswa secara manual. Gunakan tindakan "Review & Setujui" atau "Revisi" di halaman daftar.')
                ->danger()
                ->send();
            
            $this->redirect($this->getResource()::getUrl('index'));
            return;
        }

        if ($user?->isStudent() && $this->record->status === 'approved') {
            \Filament\Notifications\Notification::make()
                ->title('Tidak Dapat Diedit')
                ->body('Jurnal yang sudah disetujui oleh Guru Pembimbing tidak dapat diubah lagi.')
                ->warning()
                ->send();
                
            $this->redirect($this->getResource()::getUrl('index'));
            return;
        }
    }

    public bool $wasRevision = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika siswa mengedit jurnal yang sebelumnya "Perlu Revisi", otomatis ubah statusnya kembali menjadi "Menunggu Review"
        if (auth()->user()?->isStudent() && $this->record->status === 'revision') {
            $this->wasRevision = true;
            $data['status'] = 'submitted';
            $data['teacher_note'] = null; // Hapus catatan lama guru
        }

        return $data;
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        if ($this->wasRevision) {
            return \Filament\Notifications\Notification::make()
                ->success()
                ->title('Revisi Berhasil Dikirim')
                ->body('Jurnal Anda telah diperbarui dan dikirim kembali ke Guru untuk direview.');
        }

        return parent::getSavedNotification();
    }
}
