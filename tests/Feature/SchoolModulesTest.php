<?php

use App\Enums\Academic\DayOfWeek;
use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use App\Livewire\Pages\Academic\AcademicClassCreate;
use App\Livewire\Pages\Academic\SubjectCreate;
use App\Livewire\Pages\Biometric\LogCreate;
use App\Livewire\Pages\Employee\TeacherCreate;
use App\Livewire\Pages\Marks\MarkCreate;
use App\Livewire\Pages\Sales\SaleCreate;
use App\Livewire\Pages\Timetable\TimetableCreate;
use App\Models\BiometricDevice;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Employee;
use App\Models\SaleItem;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('school module pages can be rendered', function () {
    $user = User::factory()->create();

    foreach ([
        'teachers.index',
        'teachers.create',
        'staff.index',
        'staff.create',
        'classes.index',
        'classes.create',
        'subjects.index',
        'subjects.create',
        'academic-years.index',
        'academic-years.create',
        'timetables.index',
        'timetables.create',
        'marks.index',
        'marks.create',
        'attendance.students',
        'attendance.students.create',
        'attendance.staff',
        'attendance.staff.create',
        'biometric.devices.index',
        'biometric.devices.create',
        'biometric.logs.index',
        'biometric.logs.create',
        'fees.index',
        'fees.create',
        'fees.structures.create',
        'expenses.index',
        'expenses.create',
        'payroll.index',
        'payroll.create',
        'transport.index',
        'transport.create',
        'transport.assignments.index',
        'transport.assignments.create',
        'sales.items.index',
        'sales.items.create',
        'sales.index',
        'sales.create',
        'library.books.index',
        'library.books.create',
        'library.loans.index',
        'library.loans.create',
        'library.materials.index',
        'library.materials.create',
        'reports.index',
        'reports.create',
        'reports.students',
        'reports.attendance',
        'reports.finance',
        'reports.exams',
        'roles.index',
        'roles.create',
        'permissions.index',
        'permissions.create',
        'users.index',
        'users.create',
        'settings.index',
        'settings.create',
    ] as $route) {
        $this->actingAs($user)->get(route($route))->assertOk();
    }
});

test('academic teacher timetable and marks records can be created', function () {
    $user = User::factory()->create();

    $academicYear = AcademicYear::create([
        'name' => '1405',
        'starts_at' => '2026-03-21',
        'ends_at' => '2027-03-20',
        'is_active' => true,
    ]);

    Livewire::actingAs($user)
        ->test(AcademicClassCreate::class)
        ->set('form.name', 'Class One')
        ->set('form.grade_level', '1')
        ->set('form.status', 'active')
        ->set('form.section_names', 'A')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('classes.index'));

    $class = AcademicClass::query()->where('name', 'Class One')->firstOrFail();
    $section = Section::query()->where('class_id', $class->id)->where('name', 'A')->firstOrFail();

    Livewire::actingAs($user)
        ->test(TeacherCreate::class)
        ->set('form.first_name', 'Ahmad')
        ->set('form.last_name', 'Rahimi')
        ->set('form.gender', StudentGender::Male->value)
        ->set('form.phone', '0700000000')
        ->set('form.teacher_type', 'full_time')
        ->set('form.hired_at', '2026-04-01')
        ->set('form.contract_type', ContractType::Permanent->value)
        ->set('form.base_salary', '100')
        ->set('form.status', EmployeeStatus::Active->value)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('teachers.index'));

    $teacher = Employee::query()->where('first_name', 'Ahmad')->firstOrFail();

    Livewire::actingAs($user)
        ->test(SubjectCreate::class)
        ->set('form.class_id', (string) $class->id)
        ->set('form.name', 'Math')
        ->set('form.code', 'MATH-1')
        ->set('form.status', 'active')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('subjects.index'));

    $subject = Subject::query()->where('name', 'Math')->firstOrFail();

    Livewire::actingAs($user)
        ->test(TimetableCreate::class)
        ->set('form.class_id', (string) $class->id)
        ->set('form.section_id', (string) $section->id)
        ->set('form.subject_id', (string) $subject->id)
        ->set('form.teacher_id', (string) $teacher->id)
        ->set('form.academic_year_id', (string) $academicYear->id)
        ->set('form.day_of_week', DayOfWeek::Saturday->value)
        ->set('form.start_time', '08:00')
        ->set('form.end_time', '08:45')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('timetables.index'));

    $student = Student::create([
        'first_name' => 'Laila',
        'last_name' => 'Rahimi',
        'father_name' => 'Karim',
        'grandfather_name' => 'Rahman',
        'tazkira_number' => 'TK-9001',
        'gender' => StudentGender::Female->value,
        'class_id' => $class->id,
        'section_id' => $section->id,
        'academic_year_id' => $academicYear->id,
        'admission_date' => '2026-04-01',
        'status' => StudentStatus::Active->value,
    ]);

    Livewire::actingAs($user)
        ->test(MarkCreate::class)
        ->set('form.class_id', (string) $class->id)
        ->set('form.section_id', (string) $section->id)
        ->set('form.student_id', (string) $student->id)
        ->set('form.academic_year_id', (string) $academicYear->id)
        ->set('form.subject_id', (string) $subject->id)
        ->set('form.teacher_id', (string) $teacher->id)
        ->set('form.term', 'first')
        ->set('form.exam_name', 'Midterm')
        ->set('form.exam_type', 'written')
        ->set('form.marks_obtained', '85')
        ->set('form.total_marks', '100')
        ->set('form.result_date', '2026-05-01')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('marks.index'));

    $this->assertDatabaseHas('student_results', [
        'student_id' => $student->id,
        'subject_id' => $subject->id,
        'grade' => 'B',
    ]);
});

test('student sale reduces item stock', function () {
    $user = User::factory()->create();

    $item = SaleItem::create([
        'sku' => 'BOOK-001',
        'name' => 'Math Book',
        'category' => 'book',
        'unit_price' => 100,
        'stock_quantity' => 5,
        'reorder_level' => 1,
        'status' => 'active',
    ]);

    Livewire::actingAs($user)
        ->test(SaleCreate::class)
        ->set('form.invoice_number', 'INV-TEST-001')
        ->set('form.sold_at', '2026-06-15')
        ->set('form.sale_item_id', (string) $item->id)
        ->set('form.quantity', '2')
        ->set('form.paid_amount', '200')
        ->set('form.status', 'paid')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('sales.index'));

    expect($item->refresh()->stock_quantity)->toBe(3);

    $this->assertDatabaseHas('student_sales', [
        'invoice_number' => 'INV-TEST-001',
        'total_amount' => 200,
    ]);
});

test('biometric log creates attendance summary', function () {
    $user = User::factory()->create();

    $academicYear = AcademicYear::create([
        'name' => '1405',
        'starts_at' => '2026-03-21',
        'ends_at' => '2027-03-20',
        'is_active' => true,
    ]);

    $class = AcademicClass::create([
        'name' => 'Class Two',
        'grade_level' => 2,
        'status' => 'active',
    ]);

    $section = Section::create([
        'class_id' => $class->id,
        'name' => 'A',
        'capacity' => 30,
    ]);

    $student = Student::create([
        'first_name' => 'Omid',
        'last_name' => 'Ahmadi',
        'father_name' => 'Nasir',
        'grandfather_name' => 'Karim',
        'tazkira_number' => 'TK-9010',
        'gender' => StudentGender::Male->value,
        'class_id' => $class->id,
        'section_id' => $section->id,
        'academic_year_id' => $academicYear->id,
        'admission_date' => '2026-04-01',
        'status' => StudentStatus::Active->value,
        'biometric_uid' => 555,
    ]);

    $device = BiometricDevice::create([
        'name' => 'Main Gate',
        'ip_address' => '192.168.1.50',
    ]);

    Livewire::actingAs($user)
        ->test(LogCreate::class)
        ->set('form.biometric_uid', '555')
        ->set('form.device_id', (string) $device->id)
        ->set('form.timestamp', '2026-06-15T08:10')
        ->set('form.log_type', 'check_in')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('biometric.logs.index'));

    $summary = \App\Models\AttendanceSummary::query()->firstOrFail();

    expect($summary->date->toDateString())->toBe('2026-06-15');

    $this->assertDatabaseHas('attendance_summaries', [
        'person_id' => $student->id,
        'person_type' => Student::class,
        'check_in' => '08:10:00',
    ]);
});

test('permissions can be assigned to roles', function () {
    $user = User::factory()->create();

    Permission::create(['name' => 'students.view', 'guard_name' => 'web']);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Pages\Access\RoleCreate::class)
        ->set('form.name', 'Registrar')
        ->set('form.permissions', ['students.view'])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('roles.index'));

    expect(Role::findByName('Registrar')->hasPermissionTo('students.view'))->toBeTrue();
});
