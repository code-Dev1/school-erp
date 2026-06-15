<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'academic_year_id',
        'term',
        'semester',
        'exam_name',
        'exam_type',
        'marks_obtained',
        'total_marks',
        'grade',
        'remarks',
        'note',
        'recorded_by',
        'result_date',
        'exam_date',
    ];

    protected function casts(): array
    {
        return [
            'marks_obtained' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'result_date' => 'date',
            'exam_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
