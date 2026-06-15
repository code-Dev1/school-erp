<?php

use App\Enums\Students\StudentGender;
use App\Livewire\Pages\Student\StudentCreate;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Livewire\Livewire;

test('student pages can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('student-index'))->assertOk();
    $this->actingAs($user)->get(route('student-create'))->assertOk();
});

test('a student can be created from the student livewire form', function () {
    $user = User::factory()->create();

    $academicYear = AcademicYear::create([
        'name' => '1405',
        'starts_at' => '2026-03-21',
        'ends_at' => '2027-03-20',
        'is_active' => true,
    ]);

    $class = AcademicClass::create([
        'name' => 'صنف اول',
        'grade_level' => 1,
        'is_active' => true,
    ]);

    $section = Section::create([
        'class_id' => $class->id,
        'name' => 'الف',
        'code' => '1-A',
        'is_active' => true,
    ]);

    $guardian = Guardian::create([
        'name' => 'Karim Rahimi',
        'first_name' => 'Karim',
        'last_name' => 'Rahimi',
        'father_name' => 'Rahman',
        'phone' => '0799000000',
        'contact_number' => '0799000000',
        'status' => 'active',
    ]);

    Livewire::actingAs($user)
        ->test(StudentCreate::class)
        ->set('form.asas_number', 'ASAS-1001')
        ->set('form.guardian_id', (string) $guardian->id)
        ->set('form.guardian_relationship', '__custom')
        ->set('form.custom_guardian_relationship', 'mama')
        ->set('form.first_name', 'Laila')
        ->set('form.last_name', 'Rahimi')
        ->set('form.father_name', 'Karim')
        ->set('form.grandfather_name', 'Rahman')
        ->set('form.tazkira_number', 'TK-1001')
        ->set('form.gender', StudentGender::Female->value)
        ->set('form.student_type', 'new')
        ->set('form.class_id', (string) $class->id)
        ->set('form.section_id', (string) $section->id)
        ->set('form.academic_year_id', (string) $academicYear->id)
        ->set('form.admission_date', '2026-04-01')
        ->set('form.status', 'active')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('student-index'));

    expect(Student::query()->where('asas_number', 'ASAS-1001')->exists())->toBeTrue();
    $student = Student::query()->where('asas_number', 'ASAS-1001')->firstOrFail();

    $this->assertDatabaseHas('student_guardian', [
        'student_id' => $student->id,
        'guardian_id' => $guardian->id,
        'relationship' => 'mama',
        'is_primary' => true,
    ]);
});
