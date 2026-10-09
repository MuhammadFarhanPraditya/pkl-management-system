<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buat User otomatis (tanpa email, password dummy)
        // karena siswa akan aktivasi sendiri nanti.
        $user = User::create([
            'name'     => $data['user']['name'],
            'email'    => null,
            'password' => Hash::make(Str::random(32)),
            'role'     => 'siswa',
        ]);

        unset($data['user']);
        $data['user_id'] = $user->id;

        return $data;
    }
}
