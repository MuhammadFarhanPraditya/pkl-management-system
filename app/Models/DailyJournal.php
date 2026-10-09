<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'attendance_status',
        'start_time',
        'end_time',
        'work_description',
        'documentation_path',
        'status',
        'teacher_note',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Siswa pemilik jurnal ini.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
