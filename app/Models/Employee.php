<?php

namespace App\Models;

use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Students\StudentGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $fillable = [
        'employee_id',
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
        'address',
        'contact_number',
        'phone',
        'whatsapp_number',
        'email',
        'blood_group',
        'type',
        'teacher_type',
        'custom_type',
        'job_title',
        'custom_job_title',
        'department',
        'education_level',
        'field_of_study',
        'hired_at',
        'contract_type',
        'base_salary',
        'salary',
        'bank_account',
        'reports_to',
        'status',
        'biometric_uid',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hired_at' => 'date',
            'gender' => StudentGender::class,
            'type' => EmployeeType::class,
            'contract_type' => ContractType::class,
            'status' => EmployeeStatus::class,
            'base_salary' => 'decimal:2',
            'biometric_uid' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Employee $employee): void {
            if (! $employee->employee_id) {
                $employee->employee_id = static::generateEmployeeId($employee->type);
            }

            if (! $employee->name) {
                $employee->name = trim($employee->first_name.' '.$employee->last_name);
            }

            if (! $employee->salary && $employee->base_salary) {
                $employee->salary = $employee->base_salary;
            }
        });
    }

    public static function generateEmployeeId(EmployeeType|string|null $type = null): string
    {
        $prefix = ($type instanceof EmployeeType ? $type->value : $type) === EmployeeType::Teacher->value ? 'TCH' : 'EMP';
        $nextId = ((int) static::withTrashed()->max('id')) + 1;

        return $prefix.'-'.now()->format('Y').'-'.str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'reports_to');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'teacher_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(StudentResult::class, 'teacher_id');
    }

    public function attendanceSummaries(): MorphMany
    {
        return $this->morphMany(AttendanceSummary::class, 'person');
    }

    public function salaryComponents(): HasMany
    {
        return $this->hasMany(SalaryComponent::class);
    }

    public function payrollRecords(): HasMany
    {
        return $this->hasMany(PayrollRecord::class);
    }

    public function salaries(): MorphMany
    {
        return $this->morphMany(Salary::class, 'payable');
    }
}
