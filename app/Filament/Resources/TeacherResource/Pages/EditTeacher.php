<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Isi form dengan data dari User yang berelasi saat halaman edit dibuka.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;
        $data['user']['name']  = $user?->name;
        $data['user']['email'] = $user?->email;
        $data['user']['password'] = ''; // Jangan tampilkan password lama

        return $data;
    }

    /**
     * Sebelum simpan: update data User yang sudah ada (nama, email, dan password jika diisi).
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $userData = $data['user'] ?? [];
        unset($data['user']);

        $user = $this->record->user;

        if ($user) {
            $updateData = [
                'name'  => $userData['name'] ?? $user->name,
                'email' => $userData['email'] ?? $user->email,
            ];

            // Hanya update password jika admin mengisi field password
            if (! empty($userData['password'])) {
                $updateData['password'] = Hash::make($userData['password']);
            }

            $user->update($updateData);
        }

        return $data;
    }
}
