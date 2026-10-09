<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AccountActivationTest extends TestCase
{
    use RefreshDatabase;

    protected function createUnactivatedStudent(array $overrides = []): array
    {
        $user = User::create(array_merge([
            'name' => 'Test Student',
            'email' => null,
            'password' => Hash::make(Str::random(32)),
            'role' => 'siswa',
        ], $overrides['user'] ?? []));

        $student = Student::create(array_merge([
            'user_id' => $user->id,
            'nisn' => '0012345678',
            'nis' => '21001',
            'class_name' => 'XI RPL 1',
            'major' => 'RPL',
            'date_of_birth' => '2008-05-15',
        ], $overrides['student'] ?? []));

        return ['user' => $user, 'student' => $student];
    }

    protected function createUnactivatedTeacher(array $overrides = []): array
    {
        $user = User::create(array_merge([
            'name' => 'Test Teacher',
            'email' => null,
            'password' => Hash::make(Str::random(32)),
            'role' => 'guru',
        ], $overrides['user'] ?? []));

        $teacher = Teacher::create(array_merge([
            'user_id' => $user->id,
            'nip' => '198001012005011001',
            'phone_number' => '081234567890',
        ], $overrides['teacher'] ?? []));

        return ['user' => $user, 'teacher' => $teacher];
    }

    // ===== HALAMAN AKTIVASI =====

    public function test_activation_form_is_accessible(): void
    {
        $response = $this->get(route('activate.form'));
        $response->assertOk();
        $response->assertSee('Aktivasi Akun');
        $response->assertSee('Saya Siswa');
        $response->assertSee('Saya Guru');
    }

    // ===== AKTIVASI SISWA =====

    public function test_student_can_verify_with_nisn_and_dob(): void
    {
        $data = $this->createUnactivatedStudent();

        $response = $this->post(route('activate.verify'), [
            'role' => 'siswa',
            'nisn' => '0012345678',
            'date_of_birth' => '2008-05-15',
        ]);

        $response->assertOk();
        $response->assertSee('Test Student');
        $response->assertSee('XI RPL 1');
    }

    public function test_student_verify_fails_with_wrong_dob(): void
    {
        $data = $this->createUnactivatedStudent();

        $response = $this->post(route('activate.verify'), [
            'role' => 'siswa',
            'nisn' => '0012345678',
            'date_of_birth' => '2009-01-01', // wrong date
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nisn');
    }

    public function test_student_can_complete_activation(): void
    {
        $data = $this->createUnactivatedStudent();
        $token = encrypt($data['student']->id . '|siswa|' . now()->timestamp);

        $response = $this->post(route('activate.complete'), [
            'token' => $token,
            'email' => 'farhan@smk.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/login');

        $data['user']->refresh();
        $this->assertEquals('farhan@smk.test', $data['user']->email);
        $this->assertTrue(Hash::check('password123', $data['user']->password));
    }

    public function test_student_cannot_activate_twice(): void
    {
        $data = $this->createUnactivatedStudent();

        // Activate first time
        $data['user']->update(['email' => 'already@smk.test']);

        $response = $this->post(route('activate.verify'), [
            'role' => 'siswa',
            'nisn' => '0012345678',
            'date_of_birth' => '2008-05-15',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nisn');
    }

    // ===== AKTIVASI GURU =====

    public function test_teacher_can_verify_with_nip_and_phone(): void
    {
        $data = $this->createUnactivatedTeacher();

        $response = $this->post(route('activate.verify'), [
            'role' => 'guru',
            'nip' => '198001012005011001',
            'phone_number' => '081234567890',
        ]);

        $response->assertOk();
        $response->assertSee('Test Teacher');
    }

    public function test_teacher_verify_fails_with_wrong_phone(): void
    {
        $data = $this->createUnactivatedTeacher();

        $response = $this->post(route('activate.verify'), [
            'role' => 'guru',
            'nip' => '198001012005011001',
            'phone_number' => '000000000000', // wrong phone
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nip');
    }

    public function test_teacher_can_complete_activation(): void
    {
        $data = $this->createUnactivatedTeacher();
        $token = encrypt($data['teacher']->id . '|guru|' . now()->timestamp);

        $response = $this->post(route('activate.complete'), [
            'token' => $token,
            'email' => 'budi.guru@smk.test',
            'password' => 'securepass123',
            'password_confirmation' => 'securepass123',
        ]);

        $response->assertRedirect('/admin/login');

        $data['user']->refresh();
        $this->assertEquals('budi.guru@smk.test', $data['user']->email);
    }

    // ===== TOKEN SECURITY =====

    public function test_expired_token_is_rejected(): void
    {
        $data = $this->createUnactivatedStudent();
        // Token with timestamp 20 minutes ago (> 15 min limit)
        $token = encrypt($data['student']->id . '|siswa|' . (now()->timestamp - 1200));

        $response = $this->post(route('activate.complete'), [
            'token' => $token,
            'email' => 'test@smk.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('activate.form'));
        $response->assertSessionHasErrors('token');
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::factory()->create(['email' => 'taken@smk.test']);
        $data = $this->createUnactivatedStudent();
        $token = encrypt($data['student']->id . '|siswa|' . now()->timestamp);

        $response = $this->post(route('activate.complete'), [
            'token' => $token,
            'email' => 'taken@smk.test', // already used
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
