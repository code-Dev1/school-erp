<?php

namespace App\Support\School;

use App\Enums\Academic\DayOfWeek;
use App\Enums\Biometric\AttendanceStatus;
use App\Enums\Biometric\BiometricDeviceStatus;
use App\Enums\Biometric\BiometricDeviceType;
use App\Enums\Biometric\BiometricLogType;
use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Finance\FeePaymentStatus;
use App\Enums\Finance\SalaryComponentType;
use App\Enums\Reports\ReportType;
use App\Enums\Students\GuardianRelationship;
use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\BiometricDevice;
use App\Models\Employee;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Guardian;
use App\Models\LibraryBook;
use App\Models\SaleItem;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TransportService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OptionLists
{
    public static function academicClasses(): array
    {
        return AcademicClass::query()
            ->orderBy('grade_level')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function sections(int|string|null $classId = null): array
    {
        return Section::query()
            ->when($classId, fn (Builder $query) => $query->where('class_id', $classId))
            ->with('academicClass')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (Section $section): array {
                $className = $section->academicClass?->name;
                $label = $className ? $className.' / '.$section->name : $section->name;

                return [$section->id => $label];
            })
            ->all();
    }

    public static function academicYears(): array
    {
        return AcademicYear::query()
            ->orderByDesc('id')
            ->pluck('name', 'id')
            ->all();
    }

    public static function guardians(string $search = ''): array
    {
        return Guardian::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $search = trim($search);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('tazkira_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(25)
            ->get()
            ->mapWithKeys(fn (Guardian $guardian): array => [$guardian->id => self::guardianLabel($guardian)])
            ->all();
    }

    public static function students(int|string|null $classId = null, int|string|null $sectionId = null): array
    {
        return Student::query()
            ->when($classId, fn (Builder $query) => $query->where('class_id', $classId))
            ->when($sectionId, fn (Builder $query) => $query->where('section_id', $sectionId))
            ->orderBy('name')
            ->limit(150)
            ->get()
            ->mapWithKeys(fn (Student $student): array => [$student->id => self::studentLabel($student)])
            ->all();
    }

    public static function subjects(): array
    {
        return Subject::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function teachers(): array
    {
        return self::employees(EmployeeType::Teacher);
    }

    public static function staff(): array
    {
        return self::employees(EmployeeType::Staff);
    }

    public static function allEmployees(): array
    {
        return Employee::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Employee $employee): array => [$employee->id => self::employeeLabel($employee)])
            ->all();
    }

    public static function managers(): array
    {
        return Employee::query()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Employee $employee): array => [$employee->id => self::employeeLabel($employee)])
            ->all();
    }

    public static function biometricDevices(): array
    {
        return BiometricDevice::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function feeTypes(): array
    {
        return FeeType::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    public static function feeStructures(): array
    {
        return FeeStructure::query()
            ->with(['academicClass', 'feeType', 'academicYear'])
            ->orderByDesc('id')
            ->get()
            ->mapWithKeys(function (FeeStructure $feeStructure): array {
                $label = trim(($feeStructure->academicClass?->name ?? 'صنف').' / '.($feeStructure->feeType?->name ?? 'فیس').' / '.($feeStructure->academicYear?->name ?? 'سال'));

                return [$feeStructure->id => $label.' - '.$feeStructure->amount];
            })
            ->all();
    }

    public static function libraryBooks(): array
    {
        return LibraryBook::query()
            ->where('available_copies', '>', 0)
            ->orderBy('title')
            ->get()
            ->mapWithKeys(fn (LibraryBook $book): array => [$book->id => $book->title.($book->author ? ' - '.$book->author : '')])
            ->all();
    }

    public static function transportServices(): array
    {
        return TransportService::query()
            ->orderBy('route_name')
            ->orderBy('vehicle_plate_number')
            ->get()
            ->mapWithKeys(fn (TransportService $service): array => [$service->id => trim($service->vehicle_plate_number.' / '.$service->driver_name.' / '.$service->route_name)])
            ->all();
    }

    public static function saleItems(): array
    {
        return SaleItem::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (SaleItem $item): array => [$item->id => $item->name.' - '.$item->unit_price.' ('.$item->stock_quantity.')'])
            ->all();
    }

    public static function roles(): array
    {
        return Role::query()
            ->orderBy('name')
            ->pluck('name', 'name')
            ->all();
    }

    public static function permissions(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->pluck('name', 'name')
            ->all();
    }

    public static function guardianRelationships(): array
    {
        return [
            GuardianRelationship::Father->value => 'پدر',
            GuardianRelationship::Mother->value => 'مادر',
            GuardianRelationship::Uncle->value => 'کاکا',
            GuardianRelationship::Brother->value => 'برادر',
            GuardianRelationship::Guardian->value => 'سرپرست',
            '__custom' => 'دیگر',
        ];
    }

    public static function studentGenders(): array
    {
        return [
            StudentGender::Male->value => 'ذکور',
            StudentGender::Female->value => 'اناث',
        ];
    }

    public static function studentStatuses(): array
    {
        return [
            StudentStatus::Active->value => 'فعال',
            StudentStatus::Transferred->value => 'تبدیل شده',
            StudentStatus::Graduated->value => 'فارغ',
            StudentStatus::Expelled->value => 'اخراج شده',
        ];
    }

    public static function studentTypes(): array
    {
        return [
            'new' => 'جدید',
            'transferred' => 'تبدیلی',
        ];
    }

    public static function guardianStatuses(): array
    {
        return [
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
        ];
    }

    public static function activeStatuses(): array
    {
        return [
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
        ];
    }

    public static function employeeStatuses(): array
    {
        return [
            EmployeeStatus::Active->value => 'فعال',
            EmployeeStatus::Inactive->value => 'غیرفعال',
            EmployeeStatus::Resigned->value => 'استعفا داده',
            EmployeeStatus::Terminated->value => 'برکنار شده',
        ];
    }

    public static function attendanceStatuses(): array
    {
        return [
            AttendanceStatus::Present->value => 'حاضر',
            AttendanceStatus::Absent->value => 'غیر حاضر',
            AttendanceStatus::Late->value => 'ناوقت',
            AttendanceStatus::Excused->value => 'رخصت',
        ];
    }

    public static function biometricDeviceTypes(): array
    {
        return [
            BiometricDeviceType::Zkteco->value => 'ZKTeco',
            BiometricDeviceType::Other->value => 'دیگر',
        ];
    }

    public static function biometricDeviceStatuses(): array
    {
        return [
            BiometricDeviceStatus::Active->value => 'فعال',
            BiometricDeviceStatus::Inactive->value => 'غیرفعال',
            BiometricDeviceStatus::Maintenance->value => 'زیر ترمیم',
        ];
    }

    public static function biometricLogTypes(): array
    {
        return [
            BiometricLogType::CheckIn->value => 'ورود',
            BiometricLogType::CheckOut->value => 'خروج',
        ];
    }

    public static function feePaymentStatuses(): array
    {
        return [
            FeePaymentStatus::Paid->value => 'پرداخت شده',
            FeePaymentStatus::Pending->value => 'در انتظار',
            FeePaymentStatus::Partial->value => 'نیمه پرداخت',
            FeePaymentStatus::Exempted->value => 'معاف',
        ];
    }

    public static function salaryComponentTypes(): array
    {
        return [
            SalaryComponentType::Allowance->value => 'اضافه',
            SalaryComponentType::Deduction->value => 'کسر',
        ];
    }

    public static function salaryStatuses(): array
    {
        return [
            'pending' => 'در انتظار',
            'paid' => 'پرداخت شده',
        ];
    }

    public static function transportStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
        ];
    }

    public static function saleCategories(): array
    {
        return [
            'book' => 'Book',
            'uniform' => 'Uniform',
            'stationery' => 'Stationery',
            'material' => 'Material',
            'other' => 'Other',
        ];
    }

    public static function saleStatuses(): array
    {
        return [
            'paid' => 'Paid',
            'partial' => 'Partial',
            'unpaid' => 'Unpaid',
            'cancelled' => 'Cancelled',
        ];
    }

    public static function contractTypes(): array
    {
        return [
            ContractType::Permanent->value => 'دایمی',
            ContractType::Contract->value => 'قراردادی',
            ContractType::Hourly->value => 'ساعتی',
        ];
    }

    public static function teacherTypes(): array
    {
        return [
            'full_time' => 'تمام وقت',
            'part_time' => 'نیمه وقت',
            'contract' => 'قراردادی',
        ];
    }

    public static function staffJobTitles(): array
    {
        return [
            'administrator' => 'اداری',
            'accountant' => 'محاسب',
            'registrar' => 'ثبت نام',
            'librarian' => 'کتابدار',
            'driver' => 'راننده',
            'guard' => 'نگهبان',
            '__custom' => 'دیگر',
        ];
    }

    public static function daysOfWeek(): array
    {
        return [
            DayOfWeek::Saturday->value => 'شنبه',
            DayOfWeek::Sunday->value => 'یکشنبه',
            DayOfWeek::Monday->value => 'دوشنبه',
            DayOfWeek::Tuesday->value => 'سه شنبه',
            DayOfWeek::Wednesday->value => 'چهارشنبه',
            DayOfWeek::Thursday->value => 'پنجشنبه',
            DayOfWeek::Friday->value => 'جمعه',
        ];
    }

    public static function terms(): array
    {
        return [
            'first' => 'دوره اول',
            'second' => 'دوره دوم',
            'final' => 'نهایی',
        ];
    }

    public static function examTypes(): array
    {
        return [
            'written' => 'تحریری',
            'oral' => 'تقریری',
            'practical' => 'عملی',
            'homework' => 'کارخانگی',
        ];
    }

    public static function reportTypes(): array
    {
        return [
            ReportType::Students->value => 'شاگردان',
            ReportType::StudentAttendance->value => 'حاضری شاگردان',
            ReportType::TeacherAttendance->value => 'حاضری استادان',
            ReportType::StaffAttendance->value => 'حاضری کارمندان',
            ReportType::Payroll->value => 'معاشات',
            ReportType::Expenses->value => 'مصارف',
        ];
    }

    public static function months(): array
    {
        return [
            1 => 'جنوری',
            2 => 'فبروری',
            3 => 'مارچ',
            4 => 'اپریل',
            5 => 'می',
            6 => 'جون',
            7 => 'جولای',
            8 => 'اگست',
            9 => 'سپتمبر',
            10 => 'اکتوبر',
            11 => 'نومبر',
            12 => 'دسمبر',
        ];
    }

    public static function expenseCategories(): array
    {
        return [
            'office' => 'دفتر',
            'maintenance' => 'ترمیمات',
            'transport' => 'ترانسپورت',
            'utilities' => 'خدمات',
            'salary' => 'معاش',
            'other' => 'دیگر',
        ];
    }

    public static function materialTypes(): array
    {
        return [
            'book' => 'کتاب',
            'note' => 'نوت',
            'worksheet' => 'ورق کاری',
            'video' => 'ویدیو',
            'link' => 'لینک',
            'other' => 'دیگر',
        ];
    }

    public static function libraryStatuses(): array
    {
        return [
            'available' => 'موجود',
            'unavailable' => 'ناموجود',
        ];
    }

    public static function loanStatuses(): array
    {
        return [
            'borrowed' => 'امانت',
            'returned' => 'برگشت شده',
            'overdue' => 'دیرکرد',
        ];
    }

    private static function employees(EmployeeType $type): array
    {
        return Employee::query()
            ->where('type', $type->value)
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Employee $employee): array => [$employee->id => self::employeeLabel($employee)])
            ->all();
    }

    private static function guardianLabel(Guardian $guardian): string
    {
        $name = $guardian->name ?: trim($guardian->first_name.' '.$guardian->last_name);
        $phone = $guardian->phone ?: $guardian->contact_number;

        return trim(($name ?: 'سرپرست #'.$guardian->id).($phone ? ' - '.$phone : ''));
    }

    private static function studentLabel(Student $student): string
    {
        $name = $student->name ?: trim($student->first_name.' '.$student->last_name);
        $number = $student->asas_number ?: $student->student_id;

        return trim($name.' - '.$number);
    }

    private static function employeeLabel(Employee $employee): string
    {
        $name = $employee->name ?: trim($employee->first_name.' '.$employee->last_name);
        $number = $employee->employee_id ?: '#'.$employee->id;

        return trim(($name ?: Str::headline($employee->type->value)).' - '.$number);
    }
}
