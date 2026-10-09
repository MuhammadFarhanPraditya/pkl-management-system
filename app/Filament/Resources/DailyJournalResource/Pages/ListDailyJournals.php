<?php

namespace App\Filament\Resources\DailyJournalResource\Pages;

use App\Filament\Resources\DailyJournalResource;
use App\Models\Student;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListDailyJournals extends ListRecords
{
    protected static string $resource = DailyJournalResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        return [

            // Tombol Cetak Laporan untuk Siswa
            Action::make('printMyReport')
                ->label('Cetak Laporan PKL')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(function () {
                    $placement = auth()->user()?->student?->company;
                    return $placement ? route('reports.student.print', $placement) : '#';
                }, shouldOpenInNewTab: true)
                ->visible(fn () => auth()->user()?->isStudent() && auth()->user()?->student?->company()->exists()),

            // Modal Cetak Laporan Siswa untuk Guru & Admin
            Action::make('printStudentReport')
                ->label('Cetak Rekap Siswa')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->visible(fn () => auth()->user()?->isTeacher() || auth()->user()?->isAdmin())
                ->modalHeading('Cetak Laporan Jurnal Siswa')
                ->modalDescription('Pilih siswa PKL yang ingin dicetak rekapitulasi laporannya:')
                ->schema([
                    Select::make('student_id')
                        ->label('Pilih Siswa & Tempat PKL')
                        ->options(function () {
                            $query = Student::with(['user', 'company']);
                            if (auth()->user()?->isTeacher()) {
                                $query->where('teacher_id', auth()->user()->teacher?->id);
                            }
                            return $query->get()->mapWithKeys(function ($s) {
                                $label = ($s->user?->name ?? 'Siswa') . ' (' . ($s->class_name ?? '-') . ')';
                                if ($s->company) {
                                    $label .= ' — ' . $s->company->name;
                                }
                                return [$s->id => $label];
                            });
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $placement = Student::find($data['student_id']);
                    if ($placement) {
                        return redirect()->to(route('reports.student.print', $placement));
                    }
                }),

            CreateAction::make()
                ->label('Tulis Jurnal Baru')
                ->visible(fn () => auth()->user()?->isStudent() || auth()->user()?->isAdmin()),
        ];
    }
}
