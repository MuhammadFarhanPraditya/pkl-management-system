<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherProfile extends Page
{
    protected static string | \UnitEnum | null $navigationGroup = 'Profil & Pengaturan';

    protected static ?string $navigationLabel = 'Profil & Kontak Saya';

    protected static ?string $title = 'Profil & Kontak Guru Pembimbing';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.teacher-profile';

    public string $name = '';
    public string $nip = '';
    public string $phone_number = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public static function canAccess(): bool
    {
        return auth()->user()?->isTeacher() ?? false;
    }

    public function mount(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->isTeacher()) {
            abort(403, 'Hanya guru pembimbing yang dapat mengakses halaman ini.');
        }

        $teacher = $user->teacher;

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->nip = $teacher?->nip ?? '';
        $this->phone_number = $teacher?->phone_number ?? '';
    }

    public function save(): void
    {
        $user = auth()->user();
        $teacher = $user?->teacher;

        if (! $user || ! $teacher) {
            abort(403, 'Data akun guru tidak ditemukan.');
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nip' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap beserta gelar wajib diisi.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'nip.required' => 'NIP (Nomor Induk Pegawai) wajib diisi untuk kelengkapan identitas pembimbing.',
            'phone_number.required' => 'Nomor WhatsApp / HP wajib diisi agar siswa bimbingan dapat menghubungi Anda.',
            'phone_number.regex' => 'Format nomor telepon/WhatsApp tidak valid. Contoh: 08123456789',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (! empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        $teacher->update([
            'nip' => $this->nip,
            'phone_number' => $this->phone_number,
        ]);

        $this->password = '';
        $this->password_confirmation = '';

        Notification::make()
            ->title('Profil Berhasil Diperbarui')
            ->body('Data kontak dan informasi profil Anda telah tersimpan. Siswa bimbingan sekarang dapat melihat kontak WhatsApp Anda.')
            ->success()
            ->send();
    }
}
