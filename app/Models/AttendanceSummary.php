<?php

namespace App\Models;

use App\Enums\Biometric\AttendanceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'person_type', 'date', 'status', 'check_in', 'check_out'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => AttendanceStatus::class,
        ];
    }

    public function person(): MorphTo
    {
        return $this->morphTo();
    }
}
