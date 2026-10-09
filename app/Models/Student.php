<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'class_name',
        'major',
        'phone_number',
        'date_of_birth',
        'company_id',
        'teacher_id',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Tipe casting atribut.
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Relasi ke akun User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Perusahaan tempat PKL.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relasi ke Guru Pembimbing.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relasi ke Jurnal Harian.
     */
    public function dailyJournals(): HasMany
    {
        return $this->hasMany(DailyJournal::class);
    }
}
