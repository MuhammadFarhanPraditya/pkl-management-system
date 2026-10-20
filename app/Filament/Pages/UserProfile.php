<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class UserProfile extends Page
{
    use WithFileUploads;

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static ?string $title = 'Profil Saya';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.user-profile';

    public string $name = '';
    public string $email = '';
    public string $role = '';
    public ?string $current_avatar_url = null;
    public $avatar = null;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function mount(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->current_avatar_url = $user->avatar_url ? asset('storage/' . $user->avatar_url) : null;
        $this->role = match ($user->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru Pembimbing',
            'siswa' => 'Siswa PKL',
            default => ucfirst($user->role),
        };
    }

    public function removeAvatar(): void
    {
        $user = auth()->user();
        if ($user && $user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
            $user->update(['avatar_url' => null]);
            $this->current_avatar_url = null;
            $this->avatar = null;

            Notification::make()
                ->title('Foto Profil Dihapus')
                ->success()
                ->send();
        }
    }

    public function updateProfile(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'avatar.image' => 'File harus berupa gambar (JPG, PNG, WEBP).',
            'avatar.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->avatar) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }
            $path = $this->avatar->store('avatars', 'public');
            $data['avatar_url'] = $path;
            $this->current_avatar_url = asset('storage/' . $path);
            $this->avatar = null;
        }

        $user->update($data);

        Notification::make()
            ->title('Profil Berhasil Diperbarui')
            ->body('Nama, email, dan foto profil Anda telah tersimpan.')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:6', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.different' => 'Kata sandi baru harus berbeda dari kata sandi lama.',
        ]);

        // Update password di database
        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Jaga sesi login tetap aktif agar user TIDAK ter-logout setelah ganti password
        Auth::login($user);

        // Reset form input password
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';

        Notification::make()
            ->title('Kata Sandi Berhasil Diubah')
            ->body('Kata sandi akun Anda berhasil diperbarui. Anda tetap masuk dalam sesi aktif saat ini.')
            ->success()
            ->send();
    }

    public function sendPasswordResetEmail(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->email) {
            abort(403);
        }

        $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(
            ['email' => $user->email]
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            Notification::make()
                ->title('Tautan Reset Password Dikirim')
                ->body('Instruksi pengaturan ulang kata sandi telah dikirim ke email ' . $user->email . '.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Gagal Mengirim Email')
                ->body(__($status))
                ->danger()
                ->send();
        }
    }
}
