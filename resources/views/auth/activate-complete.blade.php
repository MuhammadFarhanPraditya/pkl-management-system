<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Aktivasi - SMK Jurnal PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">✅ Verifikasi Berhasil</h1>
            <p class="text-gray-500 mt-1">Lengkapi data login Anda</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            {{-- Identity Card --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4 mb-6">
                <p class="text-sm font-medium text-indigo-800 mb-2">Data Anda ditemukan:</p>
                <div class="space-y-1">
                    <p class="text-sm text-indigo-700">
                        <span class="font-semibold">Nama:</span> {{ $user->name }}
                    </p>
                    <p class="text-sm text-indigo-700">
                        <span class="font-semibold">Role:</span>
                        {{ $role === 'siswa' ? '🧑‍🎓 Siswa' : '👨‍🏫 Guru Pembimbing' }}
                    </p>
                    @if ($role === 'siswa')
                        <p class="text-sm text-indigo-700">
                            <span class="font-semibold">Kelas:</span> {{ $profile->class_name }} — {{ $profile->major }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Complete Activation Form --}}
            <form action="{{ route('activate.complete') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Masukkan email aktif Anda"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Email ini akan digunakan untuk login ke sistem.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                            placeholder="Ketik ulang password Anda"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                    🚀 Aktifkan Akun Saya
                </button>
            </form>
        </div>

        {{-- Back --}}
        <div class="text-center mt-4">
            <a href="{{ route('activate.form') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Kembali ke Verifikasi
            </a>
        </div>
    </div>
</body>
</html>
