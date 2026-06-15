<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'name', 'code', 'description', 'status', 'note', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(AcademicClass::class, 'class_subject_teacher', 'subject_id', 'class_id')
            ->withPivot(['teacher_id', 'academic_year_id'])
            ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'class_subject_teacher', 'subject_id', 'teacher_id')
            ->withPivot(['class_id', 'academic_year_id'])
            ->withTimestamps();
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
