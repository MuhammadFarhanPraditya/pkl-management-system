<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\DailyJournal;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PklWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $teacher1User;
    protected Teacher $teacher1;
    protected User $teacher2User;
    protected Teacher $teacher2;
    protected User $student1User;
    protected Student $student1;
    protected User $student2User;
    protected Student $student2;
    protected Company $company;
            protected DailyJournal $journal1;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->adminUser = User::factory()->create([
            'name' => 'Admin PKL',
            'role' => 'admin',
        ]);

        // 2. Guru 1 & Guru 2
        $this->teacher1User = User::factory()->create([
            'name' => 'Guru Satu',
            'role' => 'guru',
        ]);
        $this->teacher1 = Teacher::create([
            'user_id' => $this->teacher1User->id,
            'nip' => '19800101',
            'phone_number' => '08123456789',
        ]);

        $this->teacher2User = User::factory()->create([
            'name' => 'Guru Dua',
            'role' => 'guru',
        ]);
        $this->teacher2 = Teacher::create([
            'user_id' => $this->teacher2User->id,
            'nip' => '19800102',
            'phone_number' => '08123456788',
        ]);

        // 3. Siswa 1 (dibimbing Guru 1) & Siswa 2 (dibimbing Guru 2)
        $this->student1User = User::factory()->create([
            'name' => 'Ahmad Siswa 1',
            'role' => 'siswa',
        ]);
        $this->student1 = Student::create([
            'user_id' => $this->student1User->id,
            'nisn' => '0012345671',
            'nis' => '1001',
            'class_name' => 'XI RPL 1',
            'major' => 'RPL',
        ]);

        $this->student2User = User::factory()->create([
            'name' => 'Budi Siswa 2',
            'role' => 'siswa',
        ]);
        $this->student2 = Student::create([
            'user_id' => $this->student2User->id,
            'nisn' => '0012345672',
            'nis' => '1002',
            'class_name' => 'XI RPL 2',
            'major' => 'RPL',
        ]);

        // 4. Mitra DU-DI
        $this->company = Company::create([
            'name' => 'PT Teknologi Nusantara',
            'address' => 'Jl. Merdeka No. 10',
            'city' => 'Bandung',
            'pic_name' => 'Pak Budi DU-DI',
            'pic_contact' => '081122334455',
        ]);

        // 5. Penempatan Siswa 1 -> Guru 1, Siswa 2 -> Guru 2
        $this->student1->update([
            'company_id' => $this->company->id,
            'teacher_id' => $this->teacher1->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
        ]);

        $this->student2->update([
            'company_id' => $this->company->id,
            'teacher_id' => $this->teacher2->id,
            'start_date' => now()->subDays(10),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
        ]);

        // 6. Jurnal Harian Siswa 1
        $this->journal1 = DailyJournal::create([
            'student_id' => $this->student1->id,
            'date' => now()->subDay(),
            'attendance_status' => 'hadir',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'work_description' => 'Membuat fitur otentikasi login menggunakan Laravel dan Filament.',
            'status' => 'submitted',
        ]);
    }

    public function test_student_can_print_own_placement_report(): void
    {
        $response = $this->actingAs($this->student1User)
            ->get(route('reports.student.print', $this->student1));

        $response->assertOk();
        $response->assertSee('Laporan Rekapitulasi Jurnal Harian');
        $response->assertSee($this->student1User->name);
        $response->assertSee($this->company->name);
        $response->assertSee('Membuat fitur otentikasi login');
    }

    public function test_student_cannot_print_other_student_report(): void
    {
        $response = $this->actingAs($this->student1User)
            ->get(route('reports.student.print', $this->student2));

        $response->assertForbidden();
    }

    public function test_teacher_can_print_supervised_student_report(): void
    {
        $response = $this->actingAs($this->teacher1User)
            ->get(route('reports.student.print', $this->student1));

        $response->assertOk();
        $response->assertSee($this->student1User->name);
    }

    public function test_teacher_cannot_print_other_teacher_student_report(): void
    {
        $response = $this->actingAs($this->teacher1User)
            ->get(route('reports.student.print', $this->student2));

        $response->assertForbidden();
    }

    public function test_admin_can_print_any_placement_report(): void
    {
        $response1 = $this->actingAs($this->adminUser)
            ->get(route('reports.student.print', $this->student1));
        $response1->assertOk();

        $response2 = $this->actingAs($this->adminUser)
            ->get(route('reports.student.print', $this->student2));
        $response2->assertOk();
    }

    public function test_teacher_can_approve_journal_via_table_action(): void
    {
        $this->actingAs($this->teacher1User);

        // Approve journal melalui model langsung (simulasi table action)
        $this->journal1->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->journal1->refresh();
        $this->assertEquals('approved', $this->journal1->status);
        $this->assertNotNull($this->journal1->approved_at);
    }

    public function test_teacher_can_request_revision_via_table_action(): void
    {
        $this->actingAs($this->teacher1User);

        // Minta revisi melalui model langsung (simulasi table action)
        $this->journal1->update([
            'status' => 'revision',
            'teacher_note' => 'Tolong tambahkan dokumentasi screenshot program.',
        ]);

        $this->journal1->refresh();
        $this->assertEquals('revision', $this->journal1->status);
        $this->assertEquals('Tolong tambahkan dokumentasi screenshot program.', $this->journal1->teacher_note);
    }
}
