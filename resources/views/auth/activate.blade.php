<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun - SMK Jurnal PKL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Header --}}
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">🎓 Aktivasi Akun</h1>
            <p class="text-gray-500 mt-1">SMK - Jurnal PKL</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Success Messages --}}
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">{{ session('status') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            {{-- Form Siswa --}}
            <form action="{{ route('activate.verify') }}" method="POST" id="form-siswa">
                @csrf
                <input type="hidden" name="role" value="siswa">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" maxlength="10"
                            placeholder="Masukkan 10 digit NISN Anda"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                    Verifikasi & Lanjutkan
                </button>
            </form>

            {{-- Info Box --}}
            <div class="mt-5 bg-blue-50 border border-blue-100 rounded-lg p-3">
                <p class="text-xs text-blue-700">
                    <strong>ℹ️ Informasi:</strong> Data Anda harus sudah didaftarkan oleh Admin sekolah terlebih dahulu.
                    Jika data tidak ditemukan, hubungi Pokja PKL sekolah Anda.
                </p>
            </div>
        </div>

        {{-- Back to Login --}}
        <div class="text-center mt-4">
            <a href="/admin/login" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                ← Kembali ke Halaman Login
            </a>
        </div>
    </div>

    </body>
</html>
