<?php

namespace App\Models;

use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'asas_number',
        'name',
        'first_name',
        'last_name',
        'father_name',
        'grandfather_name',
        'tazkira_number',
        'date_of_birth',
        'gender',
        'photo_path',
        'province',
        'district',
        'village',
        'contact_number',
        'address',
        'student_type',
        'blood_group',
        'class_id',
        'section_id',
        'academic_year_id',
        'admission_date',
        'status',
        'previous_school',
        'transfer_document',
        'biometric_uid',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
            'gender' => StudentGender::class,
            'status' => StudentStatus::class,
            'biometric_uid' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Student $student): void {
            if (! $student->student_id) {
                $student->student_id = static::generateStudentId();
            }

            if (! $student->asas_number) {
                $student->asas_number = static::generateAsasNumber();
            }

            if (! $student->name) {
                $student->name = trim($student->first_name.' '.$student->last_name);
            }
        });
    }

    public static function generateStudentId(): string
    {
        $nextId = ((int) static::withTrashed()->max('id')) + 1;

        return 'STU-'.now()->format('Y').'-'.str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }

    public static function generateAsasNumber(): string
    {
        $nextId = ((int) static::withTrashed()->max('id')) + 1;

        return now()->format('Y').str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    public function primaryGuardian(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->wherePivot('is_primary', true)
            ->withPivot(['is_primary', 'relationship'])
            ->withTimestamps();
    }

    public function academicClass(): BelongsTo
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot(['is_primary', 'relationship'])
            ->withTimestamps();
    }

    public function classHistory(): BelongsToMany
    {
        return $this->belongsToMany(AcademicClass::class, 'class_student', 'student_id', 'class_id')
            ->withPivot(['academic_year', 'status'])
            ->withTimestamps();
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(StudentTransfer::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(StudentResult::class);
    }

    public function attendanceSummaries(): MorphMany
    {
        return $this->morphMany(AttendanceSummary::class, 'person');
    }

    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'student_transport')
            ->withPivot(['route_id', 'fee_amount', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }

    public function transportRoutes(): BelongsToMany
    {
        return $this->belongsToMany(TransportRoute::class, 'student_transport', 'student_id', 'route_id')
            ->withPivot(['vehicle_id', 'fee_amount', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }

    public function announcementRecipients(): MorphMany
    {
        return $this->morphMany(AnnouncementRecipient::class, 'recipient');
    }
}
