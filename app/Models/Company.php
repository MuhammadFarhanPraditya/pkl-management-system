<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'pic_name',
        'pic_contact',
    ];

    /**
     * Relasi ke siswa yang PKL di perusahaan ini.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
