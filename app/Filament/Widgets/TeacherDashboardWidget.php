<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\TeacherProfile;
use App\Filament\Resources\DailyJournalResource;
use App\Models\DailyJournal;
use App\Models\Student;
use Filament\Widgets\Widget;

class TeacherDashboardWidget extends Widget
{
    protected string $view = 'filament.widgets.teacher-dashboard-widget';

    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->isTeacher() ?? false;
    }

    protected function getViewData(): array
    {
        $user   = auth()->user();
        $teacher = $user?->teacher;

        $students    = $teacher?->students()->with('user', 'company')->get() ?? collect();
        $teacherId   = $teacher?->id;

        $pendingCount = DailyJournal::whereHas('student', fn ($q) => $q->where('teacher_id', $teacherId))
            ->where('status', 'submitted')
            ->count();

        $pendingJournals = $pendingCount > 0
            ? DailyJournal::whereHas('student', fn ($q) => $q->where('teacher_id', $teacherId))
                ->where('status', 'submitted')
                ->with('student.user:id,name')
                ->latest('date')
                ->limit(5)
                ->get()
            : collect();

        $studentsNoPlacement = $students->whereNull('company_id');

        $profileComplete = ! empty($teacher?->nip) && ! empty($teacher?->phone_number);

        return [
            'teacher'             => $teacher,
            'students'            => $students,
            'studentsCount'       => $students->count(),
            'pendingCount'        => $pendingCount,
            'pendingJournals'     => $pendingJournals,
            'studentsNoPlacement' => $studentsNoPlacement,
            'profileComplete'     => $profileComplete,
            'profileUrl'          => TeacherProfile::getUrl(),
            'journalListUrl'      => DailyJournalResource::getUrl('index'),
            'teacherName'         => $user?->name,
        ];
    }
}
