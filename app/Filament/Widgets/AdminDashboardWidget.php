<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\DailyJournalResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\TeacherResource;
use App\Models\Company;
use App\Models\DailyJournal;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class AdminDashboardWidget extends Widget
{
    protected string $view = 'filament.widgets.admin-dashboard-widget';

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected function getViewData(): array
    {
        $today = Carbon::today()->toDateString();

        // 1 query efisien untuk seluruh statistik siswa
        $studentStats = Student::query()
            ->selectRaw('
                COUNT(*) as total,
                COUNT(company_id) as with_placement,
                COUNT(teacher_id) as with_teacher
            ')
            ->first();

        $totalStudents = (int) ($studentStats->total ?? 0);
        $studentsWithPlacement = (int) ($studentStats->with_placement ?? 0);
        $studentsWithoutPlacement = $totalStudents - $studentsWithPlacement;
        $studentsWithTeacher = (int) ($studentStats->with_teacher ?? 0);
        $studentsWithoutTeacher = $totalStudents - $studentsWithTeacher;

        $totalTeachers = Teacher::count();
        $totalCompanies = Company::count();

        // 1 query efisien untuk ringkasan jurnal
        $journalStats = DailyJournal::query()
            ->selectRaw("
                COUNT(CASE WHEN DATE(date) = ? THEN 1 END) as today_count,
                COUNT(CASE WHEN status = 'submitted' THEN 1 END) as pending_count,
                COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved_count,
                COUNT(CASE WHEN status = 'revision' THEN 1 END) as revision_count
            ", [$today])
            ->first();

        $journalsToday = (int) ($journalStats->today_count ?? 0);
        $pendingJournals = (int) ($journalStats->pending_count ?? 0);
        $approvedJournals = (int) ($journalStats->approved_count ?? 0);
        $revisionJournals = (int) ($journalStats->revision_count ?? 0);

        // Hanya ambil 5 jurnal pending dengan relasi penting
        $recentPending = DailyJournal::query()
            ->where('status', 'submitted')
            ->with([
                'student.user:id,name',
                'student.company:id,name',
                'student.teacher.user:id,name',
            ])
            ->latest('date')
            ->limit(5)
            ->get();

        return [
            'adminName' => auth()->user()?->name ?? 'Administrator',
            'todayDate' => Carbon::now()->locale('id')->translatedFormat('l, d F Y'),
            'totalStudents' => $totalStudents,
            'studentsWithPlacement' => $studentsWithPlacement,
            'studentsWithoutPlacement' => $studentsWithoutPlacement,
            'studentsWithTeacher' => $studentsWithTeacher,
            'studentsWithoutTeacher' => $studentsWithoutTeacher,
            'totalTeachers' => $totalTeachers,
            'totalCompanies' => $totalCompanies,
            'journalsToday' => $journalsToday,
            'pendingJournals' => $pendingJournals,
            'approvedJournals' => $approvedJournals,
            'revisionJournals' => $revisionJournals,
            'recentPending' => $recentPending,
            'studentsUrl' => StudentResource::getUrl('index'),
            'teachersUrl' => TeacherResource::getUrl('index'),
            'companiesUrl' => CompanyResource::getUrl('index'),
            'journalsUrl' => DailyJournalResource::getUrl('index'),
        ];
    }
}
