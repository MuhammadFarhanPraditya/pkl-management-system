<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\DailyJournal;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PklStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user?->isStudent()) {
            $studentId = $user->student?->id;

            $totalJournals = DailyJournal::where('student_id', $studentId)->count();
            $approved = DailyJournal::where('student_id', $studentId)->where('status', 'approved')->count();
            $revisions = DailyJournal::where('student_id', $studentId)->where('status', 'revision')->count();
            $submitted = DailyJournal::where('student_id', $studentId)->where('status', 'submitted')->count();

            return [
                Stat::make('Total Jurnal Terkirim', $totalJournals)
                    ->description('Seluruh riwayat jurnal')
                    ->icon('heroicon-o-document-text')
                    ->color('primary'),
                Stat::make('Jurnal Disetujui', $approved)
                    ->description('Telah divalidasi guru pembimbing')
                    ->icon('heroicon-o-check-badge')
                    ->color('success'),
                Stat::make('Menunggu / Revisi', "{$submitted} / {$revisions}")
                    ->description('Menunggu review / perlu perbaikan')
                    ->icon('heroicon-o-clock')
                    ->color($revisions > 0 ? 'danger' : 'warning'),
            ];
        }

        if ($user?->isTeacher()) {
            $teacherId = $user->teacher?->id;

            $myStudentsCount = $user->teacher?->students()->count() ?? 0;
            $pendingReview = DailyJournal::whereHas('student', fn ($q) => $q->where('teacher_id', $teacherId))
                ->where('status', 'submitted')
                ->count();
            $totalApproved = DailyJournal::whereHas('student', fn ($q) => $q->where('teacher_id', $teacherId))
                ->where('status', 'approved')
                ->count();

            return [
                Stat::make('Siswa Bimbingan', $myStudentsCount)
                    ->description('Siswa kelas 11 aktif PKL')
                    ->icon('heroicon-o-user-group')
                    ->color('info'),
                Stat::make('Jurnal Menunggu Review', $pendingReview)
                    ->description('Perlu tindakan validasi guru')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color($pendingReview > 0 ? 'warning' : 'success'),
                Stat::make('Jurnal Sudah Disetujui', $totalApproved)
                    ->description('Total jurnal tervalidasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
            ];
        }

        // Admin Stats
        $totalStudents = Student::count();
        $totalCompanies = Company::count();
        $totalPending = DailyJournal::where('status', 'submitted')->count();

        return [
            Stat::make('Total Siswa PKL', $totalStudents)
                ->description('Kelas 11 yang terdaftar')
                ->icon('heroicon-o-academic-cap')
                ->color('primary'),
            Stat::make('Mitra DU-DI', $totalCompanies)
                ->description('Perusahaan / Tempat Magang')
                ->icon('heroicon-o-building-office-2')
                ->color('info'),
            Stat::make('Jurnal Pending Se-Sekolah', $totalPending)
                ->description('Menunggu persetujuan guru')
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
