<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    /**
     * Sebelum simpan: buat akun User baru untuk guru ini, lalu set user_id di record Teacher.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name'     => $data['user']['name'],
            'email'    => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
            'role'     => 'guru',
        ]);

        // Hapus key nested 'user' dari $data agar tidak diteruskan ke Teacher model
        unset($data['user']);

        // Sisipkan user_id yang baru dibuat
        $data['user_id'] = $user->id;

        return $data;
    }
}
