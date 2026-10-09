<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman cetak rekapitulasi jurnal harian PKL siswa.
     */
    public function printStudentJournal(Student $student)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Otorisasi berdasarkan role
        if ($user->isStudent()) {
            if ($student->id !== $user->student?->id) {
                abort(403, 'Anda hanya diperbolehkan mencetak laporan PKL Anda sendiri.');
            }
        } elseif ($user->isTeacher()) {
            if ($student->teacher_id !== $user->teacher?->id && ! $user->isAdmin()) {
                abort(403, 'Anda hanya diperbolehkan mencetak laporan siswa bimbingan Anda.');
            }
        } elseif (! $user->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $student->load([
            'user',
            'company',
            'teacher.user',
            'dailyJournals' => function ($query) {
                $query->orderBy('date', 'asc');
            },
        ]);

        $journals = $student->dailyJournals;

        $stats = [
            'total' => $journals->count(),
            'hadir' => $journals->where('attendance_status', 'hadir')->count(),
            'izin' => $journals->where('attendance_status', 'izin')->count(),
            'sakit' => $journals->where('attendance_status', 'sakit')->count(),
            'approved' => $journals->where('status', 'approved')->count(),
            'pending' => $journals->whereIn('status', ['submitted', 'revision', 'draft'])->count(),
        ];

        return view('reports.daily-journal-print', [
            'student' => $student,
            'journals' => $journals,
            'stats' => $stats,
        ]);
    }

    /**
     * Tampilkan halaman cetak Surat Tugas Pembimbing PKL resmi.
     */
    public function printTeacherAssignmentLetter(\App\Models\Teacher $teacher)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Hanya admin atau guru bersangkutan yang boleh mencetak surat tugas ini
        if ($user->isTeacher()) {
            if ($teacher->id !== $user->teacher?->id && ! $user->isAdmin()) {
                abort(403, 'Anda hanya dapat mengakses surat tugas bimbingan Anda sendiri.');
            }
        } elseif (! $user->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $teacher->load([
            'user',
            'students' => function ($query) {
                $query->with(['user', 'company'])->orderBy('start_date', 'asc');
            },
        ]);

        return view('reports.teacher-assignment-letter', [
            'teacher' => $teacher,
            'students' => $teacher->students,
        ]);
    }
}

