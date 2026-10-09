<?php

namespace Tests\Feature;

use App\Filament\Pages\TeacherProfile;
use App\Filament\Widgets\TeacherProfileAlertWidget;
use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeacherFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $teacher1User;
    protected Teacher $teacher1;
    protected User $teacher2User;
    protected Teacher $teacher2;
    protected User $studentUser;
    protected Student $student;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'name' => 'Admin Pokja PKL',
            'role' => 'admin',
        ]);

        $this->teacher1User = User::factory()->create([
            'name' => 'Pak Budi Santoso',
            'email' => 'budi@smk.test',
            'role' => 'guru',
        ]);
        $this->teacher1 = Teacher::create([
            'user_id' => $this->teacher1User->id,
            'nip' => '',
            'phone_number' => '',
        ]);

        $this->teacher2User = User::factory()->create([
            'name' => 'Ibu Siti Aminah',
            'email' => 'siti@smk.test',
            'role' => 'guru',
        ]);
        $this->teacher2 = Teacher::create([
            'user_id' => $this->teacher2User->id,
            'nip' => '198502152010012008',
            'phone_number' => '081298765432',
        ]);

        $this->studentUser = User::factory()->create([
            'name' => 'Rizky Siswa',
            'role' => 'siswa',
        ]);
        $this->student = Student::create([
            'user_id' => $this->studentUser->id,
            'nisn' => '0055123456',
            'nis' => '202401',
            'class_name' => 'XI RPL 1',
            'major' => 'Rekayasa Perangkat Lunak',
        ]);

        $this->company = Company::create([
            'name' => 'PT Teknologi Kreasi Nusantara',
            'address' => 'Jl. Merdeka No. 10',
            'city' => 'Jakarta',
            'pic_name' => 'Pak Andi Mentor',
            'pic_contact' => '081122334455',
        ]);
    }

    public function test_teacher_alert_widget_appears_when_profile_is_incomplete(): void
    {
        // Teacher 1 has empty NIP & phone_number -> Alert should be visible
        $this->actingAs($this->teacher1User);
        $this->assertTrue(TeacherProfileAlertWidget::canView());

        // Teacher 2 has complete NIP & phone_number -> Alert should NOT be visible
        $this->actingAs($this->teacher2User);
        $this->assertFalse(TeacherProfileAlertWidget::canView());

        // Admin and Student should never see the teacher alert widget
        $this->actingAs($this->adminUser);
        $this->assertFalse(TeacherProfileAlertWidget::canView());

        $this->actingAs($this->studentUser);
        $this->assertFalse(TeacherProfileAlertWidget::canView());
    }

    public function test_teacher_can_access_and_update_profile_and_contact(): void
    {
        $this->actingAs($this->teacher1User);

        Livewire::test(TeacherProfile::class)
            ->assertSet('name', 'Pak Budi Santoso')
            ->assertSet('email', 'budi@smk.test')
            ->set('name', 'Budi Santoso, S.Kom.')
            ->set('nip', '198001012005011003')
            ->set('phone_number', '081234567890')
            ->call('save')
            ->assertHasNoErrors();

        // Verifikasi database terupdate
        $this->assertDatabaseHas('users', [
            'id' => $this->teacher1User->id,
            'name' => 'Budi Santoso, S.Kom.',
        ]);

        $this->assertDatabaseHas('teachers', [
            'id' => $this->teacher1->id,
            'nip' => '198001012005011003',
            'phone_number' => '081234567890',
        ]);

        // Setelah diupdate, refresh model dan pastikan widget alert otomatis hilang
        $this->teacher1->refresh();
        $this->assertFalse(TeacherProfileAlertWidget::canView());
    }

    public function test_student_cannot_access_teacher_profile_page(): void
    {
        $this->actingAs($this->studentUser);
        $this->assertFalse(TeacherProfile::canAccess());
    }

    public function test_admin_can_assign_teacher_to_multiple_placements(): void
    {
        $this->actingAs($this->adminUser);

        // Buat 3 data penempatan siswa tanpa pembimbing
        $this->student->update([
            'company_id' => $this->company->id,
            'teacher_id' => null,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'active',
        ]);

        $studentUser2 = User::factory()->create(['name' => 'Siswa Dua', 'role' => 'siswa']);
        $student2 = Student::create([
            'user_id' => $studentUser2->id,
            'nisn' => '0055123457',
            'class_name' => 'XI RPL 1',
            'major' => 'Rekayasa Perangkat Lunak',
        ]);

        $student2->update([
            'company_id' => $this->company->id,
            'teacher_id' => null,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'active',
        ]);

        // Verifikasi penempatan awalnya null
        $this->assertNull($this->student->fresh()->teacher_id);
        $this->assertNull($student2->fresh()->teacher_id);

        // Plotting guru pembimbing
        $this->student->update(['teacher_id' => $this->teacher2->id]);
        $student2->update(['teacher_id' => $this->teacher2->id]);

        $this->assertEquals($this->teacher2->id, $this->student->fresh()->teacher_id);
        $this->assertEquals($this->teacher2->id, $student2->fresh()->teacher_id);
    }

    public function test_teacher_can_print_own_assignment_letter(): void
    {
        // Plotting siswa ke teacher 2
        $this->student->update([
            'company_id' => $this->company->id,
            'teacher_id' => $this->teacher2->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($this->teacher2User);

        $response = $this->get(route('reports.teacher.letter', $this->teacher2));
        $response->assertStatus(200);
        $response->assertSee('SURAT TUGAS PEMBIMBING PRAKTEK KERJA LAPANGAN');
        $response->assertSee($this->teacher2User->name);
        $response->assertSee($this->studentUser->name);
        $response->assertSee($this->company->name);
    }

    public function test_teacher_cannot_print_other_teacher_assignment_letter(): void
    {
        $this->actingAs($this->teacher1User);

        // Teacher 1 mencoba mencetak surat tugas Teacher 2
        $response = $this->get(route('reports.teacher.letter', $this->teacher2));
        $response->assertStatus(403);
    }

    public function test_student_cannot_print_teacher_assignment_letter(): void
    {
        $this->actingAs($this->studentUser);

        $response = $this->get(route('reports.teacher.letter', $this->teacher2));
        $response->assertStatus(403);
    }

    public function test_admin_can_print_any_teacher_assignment_letter(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->get(route('reports.teacher.letter', $this->teacher2));
        $response->assertStatus(200);
        $response->assertSee('SURAT TUGAS PEMBIMBING PRAKTEK KERJA LAPANGAN');
    }
}
