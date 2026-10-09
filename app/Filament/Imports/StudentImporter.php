<?php

namespace App\Filament\Imports;

use App\Models\Student;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Number;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama Siswa')
                ->requiredMapping()
                // Deteksi otomatis header umum: nama, name, nama_siswa, nama_lengkap
                ->guessFromHeaders(['nama', 'name', 'nama_siswa', 'nama_lengkap', 'nama siswa', 'nama lengkap'])
                ->fillRecordUsing(function (Student $record, string $state): void {
                    // Jika siswa baru, buatkan akun User otomatis
                    if (! $record->user_id) {
                        $user = User::create([
                            'name'     => trim($state),
                            'email'    => null, // Belum punya email, diisi saat aktivasi
                            'password' => Hash::make(Str::random(32)), // Password acak, tidak bisa dipakai
                            'role'     => 'siswa',
                        ]);
                        $record->user_id = $user->id;
                    } else {
                        // Update nama jika data sudah ada
                        optional($record->user)->update(['name' => trim($state)]);
                    }
                }),

            ImportColumn::make('nisn')
                ->label('NISN')
                ->requiredMapping()
                // Deteksi otomatis header umum: nisn, no_nisn, no nisn
                ->guessFromHeaders(['nisn', 'no_nisn', 'no nisn', 'NISN'])
                ->rules(['required', 'max:15']),

            ImportColumn::make('nis')
                ->label('NIS (Opsional)')
                // Deteksi otomatis: nis, no_nis, nomor_induk
                ->guessFromHeaders(['nis', 'no_nis', 'no nis', 'nomor_induk', 'nomor induk'])
                ->rules(['nullable', 'max:20']),

            ImportColumn::make('class_name')
                ->label('Kelas')
                ->requiredMapping()
                // Deteksi otomatis: kelas, class, rombel, kelas_rombel
                ->guessFromHeaders(['kelas', 'class', 'rombel', 'kelas_rombel', 'kelas rombel'])
                ->rules(['required', 'max:50']),

            ImportColumn::make('major')
                ->label('Jurusan / Program Keahlian')
                ->requiredMapping()
                // Deteksi otomatis: jurusan, major, program_keahlian, kompetensi_keahlian
                ->guessFromHeaders(['jurusan', 'major', 'program_keahlian', 'kompetensi_keahlian', 'program keahlian', 'kompetensi keahlian'])
                ->rules(['required', 'max:100']),

            ImportColumn::make('date_of_birth')
                ->label('Tanggal Lahir')
                // Deteksi otomatis: tanggal_lahir, tgl_lahir, dob, birth_date
                ->guessFromHeaders(['tanggal_lahir', 'tgl_lahir', 'tanggal lahir', 'tgl lahir', 'dob', 'birth_date', 'date_of_birth'])
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    // Support format: YYYY-MM-DD, DD-MM-YYYY, DD/MM/YYYY
                    try {
                        return \Carbon\Carbon::parse($state)->format('Y-m-d');
                    } catch (\Exception) {
                        return null;
                    }
                })
                ->rules(['nullable']),

            ImportColumn::make('phone_number')
                ->label('Nomor HP / WhatsApp (Opsional)')
                // Deteksi otomatis: no_hp, phone, hp, whatsapp, telp
                ->guessFromHeaders(['no_hp', 'phone', 'hp', 'handphone', 'whatsapp', 'telp', 'telepon', 'phone_number', 'no hp', 'no. hp'])
                ->rules(['nullable', 'max:20']),
        ];
    }

    public function resolveRecord(): ?Student
    {
        // Upsert berdasarkan NISN:
        // - Jika NISN sudah ada di DB → update data yang ada
        // - Jika belum ada → buat siswa baru
        $nisn = trim($this->data['nisn'] ?? '');

        if (! empty($nisn)) {
            $existing = Student::where('nisn', $nisn)->first();
            if ($existing) {
                return $existing; // Update record lama
            }
        }

        return new Student(); // Buat record baru
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '✅ Import data siswa selesai! '
            . Number::format($import->successful_rows) . ' data berhasil diproses.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ⚠️ ' . Number::format($failedRowsCount)
                . ' baris gagal — silakan unduh laporan error untuk melihat detailnya.';
        }

        return $body;
    }
}
