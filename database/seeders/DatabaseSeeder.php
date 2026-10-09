<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\DailyJournal;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Akun Admin / Pokja PKL
        User::create([
            'name' => 'Administrator PKL',
            'email' => 'admin@smk.test',
            'password' => $defaultPassword,
            'role' => 'admin',
        ]);

        // 2. Akun & Data Guru Pembimbing
        $userGuru1 = User::create([
            'name' => 'Budi Santoso, S.Kom',
            'email' => 'guru1@smk.test',
            'password' => $defaultPassword,
            'role' => 'guru',
        ]);
        $teacher1 = Teacher::create([
            'user_id' => $userGuru1->id,
            'nip' => '198001012005011001',
            'phone_number' => '081234567801',
        ]);

        $userGuru2 = User::create([
            'name' => 'Siti Aminah, M.Kom',
            'email' => 'guru2@smk.test',
            'password' => $defaultPassword,
            'role' => 'guru',
        ]);
        $teacher2 = Teacher::create([
            'user_id' => $userGuru2->id,
            'nip' => '198502022008022002',
            'phone_number' => '081234567802',
        ]);

        // 3. Mitra DU-DI / Perusahaan Tempat Magang
        $company1 = Company::create([
            'name' => 'PT Solusi Digital Nusantara',
            'address' => 'Jl. Asia Afrika No. 120, Sukasari',
            'city' => 'Bandung',
            'pic_name' => 'Hendra Pratama (Lead Software Engineer)',
            'pic_contact' => '081298765001',
        ]);

        $company2 = Company::create([
            'name' => 'PT Cyber Network Indonesia',
            'address' => 'Gedung Cyber 2 Lantai 8, Kuningan',
            'city' => 'Jakarta Selatan',
            'pic_name' => 'Maya Putri (Network Operations Head)',
            'pic_contact' => '081298765002',
        ]);

        $company3 = Company::create([
            'name' => 'CV Inovasi Kreatif Studio',
            'address' => 'Jl. Pemuda No. 45',
            'city' => 'Surabaya',
            'pic_name' => 'Rizky Ramadhan (Art Director)',
            'pic_contact' => '081298765003',
        ]);

        $today = Carbon::today();
        $startDate = $today->copy()->subDays(14);
        $endDate = $today->copy()->addMonths(3);

        // 4. Data Siswa Kelas 11 (5 Siswa)
        $studentsData = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'siswa1@smk.test',
                'nisn' => '0071234561',
                'nis' => '21221001',
                'class_name' => 'XI RPL 1',
                'major' => 'Rekayasa Perangkat Lunak',
                'phone' => '082111110001',
                'date_of_birth' => '2008-05-15',
                'company_id' => $company1->id,
                'teacher_id' => $teacher1->id,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'siswa2@smk.test',
                'nisn' => '0071234562',
                'nis' => '21221002',
                'class_name' => 'XI RPL 1',
                'major' => 'Rekayasa Perangkat Lunak',
                'phone' => '082111110002',
                'date_of_birth' => '2008-03-22',
                'company_id' => $company1->id,
                'teacher_id' => $teacher1->id,
            ],
            [
                'name' => 'Farhan Pratama',
                'email' => 'siswa3@smk.test',
                'nisn' => '0071234563',
                'nis' => '21221003',
                'class_name' => 'XI RPL 2',
                'major' => 'Rekayasa Perangkat Lunak',
                'phone' => '082111110003',
                'date_of_birth' => '2008-08-10',
                'company_id' => $company3->id,
                'teacher_id' => $teacher1->id,
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => null,
                'nisn' => '0071234564',
                'nis' => '21221004',
                'class_name' => 'XI TKJ 1',
                'major' => 'Teknik Komputer dan Jaringan',
                'phone' => '082111110004',
                'date_of_birth' => '2008-11-30',
                'company_id' => $company2->id,
                'teacher_id' => $teacher2->id,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => null,
                'nisn' => '0071234565',
                'nis' => '21221005',
                'class_name' => 'XI TKJ 2',
                'major' => 'Teknik Komputer dan Jaringan',
                'phone' => '082111110005',
                'date_of_birth' => '2008-07-04',
                'company_id' => $company2->id,
                'teacher_id' => $teacher2->id,
            ],
        ];

        foreach ($studentsData as $idx => $s) {
            $userStudent = User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => $s['email'] ? $defaultPassword : Hash::make(\Illuminate\Support\Str::random(32)),
                'role' => 'siswa',
            ]);

            $student = Student::create([
                'user_id' => $userStudent->id,
                'nisn' => $s['nisn'],
                'nis' => $s['nis'],
                'class_name' => $s['class_name'],
                'major' => $s['major'],
                'phone_number' => $s['phone'],
                'date_of_birth' => $s['date_of_birth'],
                'company_id' => $s['company_id'],
                'teacher_id' => $s['teacher_id'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            // Buat sampel riwayat jurnal harian hanya untuk siswa yang sudah aktivasi
            if ($idx === 0) {
                // Jurnal 1: Approved (Hadir)
                DailyJournal::create([
                    'student_id' => $student->id,
                    'date' => $today->copy()->subDays(2),
                    'attendance_status' => 'hadir',
                    'start_time' => '08:00:00',
                    'end_time' => '16:30:00',
                    'work_description' => 'Mempelajari arsitektur sistem backend perusahaan dan menyiapkan environment development lokal (Docker & Laravel).',
                    'status' => 'approved',
                    'teacher_note' => 'Bagus, terus pertahankan kedisiplinannya.',
                    'approved_at' => $today->copy()->subDays(1),
                ]);

                // Jurnal 2: Revision (Izin)
                DailyJournal::create([
                    'student_id' => $student->id,
                    'date' => $today->copy()->subDays(1),
                    'attendance_status' => 'izin',
                    'start_time' => null,
                    'end_time' => null,
                    'work_description' => 'Izin mengurus perpanjangan KTP dan administrasi kependudukan di kantor kecamatan.',
                    'status' => 'revision',
                    'teacher_note' => 'Tolong lampirkan foto surat izin/bukti pengurusan.',
                ]);

                // Jurnal 3: Submitted (Hadir hari ini)
                DailyJournal::create([
                    'student_id' => $student->id,
                    'date' => $today,
                    'attendance_status' => 'hadir',
                    'start_time' => '08:30:00',
                    'end_time' => '17:00:00',
                    'work_description' => 'Melakukan integrasi API autentikasi menggunakan token JWT pada modul login serta membuat unit testing dasar.',
                    'status' => 'submitted',
                ]);
            } elseif ($idx === 1) {
                // Jurnal Siswa 2: Submitted (Hadir)
                DailyJournal::create([
                    'student_id' => $student->id,
                    'date' => $today,
                    'attendance_status' => 'hadir',
                    'start_time' => '08:00:00',
                    'end_time' => '16:00:00',
                    'work_description' => 'Membuat mockup UI untuk fitur checkout pesanan pada Figma sesuai arahan UI/UX lead.',
                    'status' => 'submitted',
                ]);
            }
        }
    }
}
