<?php

namespace App\Policies;

use App\Models\DailyJournal;
use App\Models\User;

class DailyJournalPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru', 'siswa']);
    }

    public function view(User $user, DailyJournal $journal): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $journal->student?->teacher_id === $user->teacher?->id;
        }

        if ($user->isStudent()) {
            return $journal->student_id === $user->student?->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Hanya siswa dengan penempatan aktif yang boleh buat jurnal
        return $user->isStudent() && $user->student?->company_id !== null;
    }

    public function update(User $user, DailyJournal $journal): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Guru pembimbing boleh update untuk memberikan catatan/status review
        if ($user->isTeacher()) {
            return $journal->student?->teacher_id === $user->teacher?->id;
        }

        // Siswa hanya boleh edit jika jurnal belum disetujui (draft, submitted, atau revision)
        if ($user->isStudent()) {
            return $journal->student_id === $user->student?->id
                && in_array($journal->status, ['draft', 'submitted', 'revision']);
        }

        return false;
    }

    public function delete(User $user, DailyJournal $journal): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa boleh hapus draft atau jurnal miliknya sendiri selama belum di-approve
        if ($user->isStudent()) {
            return $journal->student_id === $user->student?->id
                && $journal->status !== 'approved';
        }

        return false;
    }
}
