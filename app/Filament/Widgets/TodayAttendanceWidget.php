<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DailyJournalResource;
use App\Models\DailyJournal;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class TodayAttendanceWidget extends Widget
{
    protected string $view = 'filament.widgets.today-attendance-widget';

    protected static ?int $sort = 0;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->isStudent() ?? false;
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $student = $user?->student;
        $hasPlacement = $student && $student->company_id !== null;

        $today = Carbon::today()->toDateString();
        $todayJournal = null;

        if ($student) {
            $todayJournal = DailyJournal::where('student_id', $student->id)
                ->whereDate('date', $today)
                ->first();
        }

        return [
            'hasPlacement' => $hasPlacement,
            'student' => $student,
            'todayJournal' => $todayJournal,
            'todayDate' => Carbon::now()->locale('id')->translatedFormat('l, d F Y'),
            'createJournalUrl' => DailyJournalResource::getUrl('create'),
            'editJournalUrl' => $todayJournal ? DailyJournalResource::getUrl('edit', ['record' => $todayJournal]) : null,
            'printReportUrl' => $student ? route('reports.student.print', $student) : null,
        ];
    }
}
