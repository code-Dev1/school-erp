<?php

namespace App\Models;

use App\Enums\Students\StudentTransferStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_class_id',
        'from_section_id',
        'to_class_id',
        'to_section_id',
        'from_school',
        'to_school',
        'transfer_date',
        'reason',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'transfer_date' => 'date',
            'status' => StudentTransferStatus::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
