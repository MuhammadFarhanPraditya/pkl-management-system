<?php

namespace Tests\Feature;

use App\Filament\Pages\StudentInternshipInfo;
use App\Models\Company;
use App\Models\DailyJournal;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StudentAttendanceAndInfoTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;
    protected Student $student;
    protected User $teacherUser;
    protected Teacher $teacher;
    protected Company $company;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->teacherUser = User::create([
            'name' => 'Guru Penguji',
            'email' => 'guru@test.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        $this->teacher = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'nip' => '198001012020',
            'phone_number' => '081234567890',
        ]);

        $this->company = Company::create([
            'name' => 'PT Teknologi Maju',
            'address' => 'Jl. Merdeka No 1',
            'city' => 'Jakarta',
            'pic_name' => 'Budi Santoso',
            'pic_contact' => '089876543210',
        ]);

        $this->studentUser = User::create([
            'name' => 'Siswa Test',
            'email' => 'siswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);

        $this->student = Student::create([
            'user_id' => $this->studentUser->id,
            'nisn' => '1234567890',
            'nis' => '12345',
            'class_name' => 'XI RPL 1',
            'major' => 'RPL',
            'phone_number' => '08555555555',
        ]);

        $this->student->update([
            'company_id' => $this->company->id,
            'teacher_id' => $this->teacher->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addDays(80),
            'status' => 'active',
        ]);
    }

    public function test_student_can_record_journal_as_attendance(): void
    {
        $journal = DailyJournal::create([
            'student_id' => $this->student->id,
            'date' => now()->toDateString(),
            'attendance_status' => 'hadir',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'work_description' => 'Mengerjakan slicing UI komponen mobile',
            'status' => 'submitted',
        ]);

        $this->assertDatabaseHas('daily_journals', [
            'id' => $journal->id,
            'attendance_status' => 'hadir',
            'status' => 'submitted',
        ]);
    }

    public function test_student_can_access_internship_info_page(): void
    {
        $this->actingAs($this->studentUser);

        $response = $this->get('/admin/student-internship-info');
        $response->assertSuccessful();
        $response->assertSee('PT Teknologi Maju');
        $response->assertSee('Guru Penguji');
        $response->assertSee('Budi Santoso');
    }

    public function test_student_can_record_izin_or_sakit_without_working_hours(): void
    {
        $journal = DailyJournal::create([
            'student_id' => $this->student->id,
            'date' => now()->toDateString(),
            'attendance_status' => 'sakit',
            'work_description' => 'Izin sakit demam dan istirahat dokter',
            'status' => 'submitted',
        ]);

        $this->assertDatabaseHas('daily_journals', [
            'id' => $journal->id,
            'attendance_status' => 'sakit',
            'start_time' => null,
            'end_time' => null,
        ]);
    }

    public function test_teacher_can_view_assigned_student_journals(): void
    {
        $journal = DailyJournal::create([
            'student_id' => $this->student->id,
            'date' => now()->toDateString(),
            'attendance_status' => 'hadir',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'work_description' => 'Testing fitur aplikasi',
            'status' => 'submitted',
        ]);

        $this->actingAs($this->teacherUser);

        $response = $this->get('/admin/daily-journals');
        $response->assertSuccessful();
        $response->assertSee('Testing fitur aplikasi');
    }
}
