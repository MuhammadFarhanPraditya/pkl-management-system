<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountActivationController extends Controller
{
    /**
     * Menampilkan halaman form Aktivasi Akun.
     */
    public function showForm()
    {
        return view('auth.activate');
    }

    /**
     * Step 1: Verifikasi identitas siswa.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa',
        ]);

        return $this->verifyStudent($request);
    }

    /**
     * Verifikasi siswa menggunakan NISN + Tanggal Lahir.
     */
    private function verifyStudent(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:10',
            'date_of_birth' => 'required|date',
        ]);

        $student = Student::where('nisn', $request->nisn)
            ->whereDate('date_of_birth', $request->date_of_birth)
            ->first();

        if (! $student) {
            return back()->withErrors([
                'nisn' => 'Data tidak ditemukan. Pastikan NISN dan Tanggal Lahir Anda benar, atau hubungi Admin sekolah.',
            ])->withInput();
        }

        // Cek apakah akun sudah diaktivasi (user sudah punya email)
        $user = $student->user;
        if ($user && $user->email !== null) {
            return back()->withErrors([
                'nisn' => 'Akun dengan NISN ini sudah diaktivasi sebelumnya. Silakan login menggunakan email yang sudah terdaftar.',
            ])->withInput();
        }

        // Kirim ke step 2: isi email & password
        return view('auth.activate-complete', [
            'role' => 'siswa',
            'user' => $user,
            'profile' => $student,
            'token' => encrypt($student->id . '|siswa|' . now()->timestamp),
        ]);
    }

    /**
     * Step 2: Buat email & password, aktifkan akun.
     */
    public function complete(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            $decrypted = decrypt($request->token);
            [$profileId, $role, $timestamp] = explode('|', $decrypted);
        } catch (\Exception $e) {
            return redirect()->route('activate.form')
                ->withErrors(['token' => 'Token aktivasi tidak valid. Silakan ulangi proses dari awal.']);
        }

        // Cek token tidak expired (15 menit)
        if (now()->timestamp - (int) $timestamp > 900) {
            return redirect()->route('activate.form')
                ->withErrors(['token' => 'Token aktivasi sudah kadaluarsa. Silakan ulangi proses dari awal.']);
        }

        if ($role === 'siswa') {
            $student = Student::findOrFail($profileId);
            $user = $student->user;
        } else {
            return redirect()->route('activate.form')
                ->withErrors(['token' => 'Role tidak didukung.']);
        }

        if (! $user) {
            return redirect()->route('activate.form')
                ->withErrors(['token' => 'Data pengguna tidak ditemukan.']);
        }

        // Pastikan belum diaktivasi
        if ($user->email !== null) {
            return redirect()->route('activate.form')
                ->withErrors(['token' => 'Akun ini sudah diaktivasi sebelumnya.']);
        }

        // Aktivasi: set email dan password
        $user->update([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/admin/login')->with('status', 'Akun berhasil diaktivasi! Silakan login menggunakan email dan password yang baru saja Anda buat.');
    }
}
