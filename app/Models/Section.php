<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function academicClass()
    {
        return $this->hasMany(AcademicClass::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }
}
