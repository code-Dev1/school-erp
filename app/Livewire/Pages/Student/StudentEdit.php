<?php

namespace App\Livewire\Pages\Student;

use App\Enums\Students\GuardianRelationship;
use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentEdit extends Component
{
    use WithFileUploads;

    public Student $student;
    public $photo = null;
    public bool $removePhoto = false;
    public string $guardianSearch = '';

    public array $form = [
        'asas_number' => '',
        'guardian_id' => '',
        'guardian_relationship' => 'guardian',
        'custom_guardian_relationship' => '',
        'first_name' => '',
        'last_name' => '',
        'father_name' => '',
        'grandfather_name' => '',
        'tazkira_number' => '',
        'gender' => '',
        'date_of_birth' => '',
        'student_type' => 'new',
        'previous_school' => '',
        'class_id' => '',
        'section_id' => '',
        'academic_year_id' => '',
        'admission_date' => '',
        'status' => 'active',
        'note' => '',
    ];

    protected $listeners = [
        'guardianChanged' => 'setGuardian',
    ];

    public function mount(Student $student): void
    {
        $this->student = $student;
        $this->form = [
            'asas_number' => $student->asas_number,
            'guardian_id' => $student->primaryGuardian->first()?->id,
            'guardian_relationship' => $student->primaryGuardian->first()?->pivot->relationship ?? 'guardian',
            'custom_guardian_relationship' => '',
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'father_name' => $student->father_name,
            'grandfather_name' => $student->grandfather_name,
            'tazkira_number' => $student->tazkira_number,
            'gender' => $student->gender?->value ?? '',
            'date_of_birth' => $student->date_of_birth?->format('Y-m-d'),
            'student_type' => $student->student_type,
            'previous_school' => $student->previous_school,
            'class_id' => $student->class_id,
            'section_id' => $student->section_id,
            'academic_year_id' => $student->academic_year_id,
            'admission_date' => $student->admission_date?->format('Y-m-d'),
            'status' => $student->status?->value ?? 'active',
            'note' => $student->note,
        ];
    }

    public function setGuardian($id): void
    {
        $this->form['guardian_id'] = $id ?: null;
    }

    public function save()
    {
        $validated = $this->validate();
        $form = $validated['form'];

        $data = collect($form)
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();

        $guardianId = $data['guardian_id'] ?? null;
        $guardianRelationship = $data['guardian_relationship'] ?? GuardianRelationship::Guardian->value;

        if ($guardianRelationship === '__custom') {
            $guardianRelationship = $data['custom_guardian_relationship'] ?? GuardianRelationship::Guardian->value;
        }

        unset($data['guardian_id'], $data['guardian_relationship'], $data['custom_guardian_relationship']);

        $data['name'] = trim(($data['first_name'] ?? '').' '.($data['last_name'] ?? ''));
        $data['province'] = null;
        $data['district'] = null;
        $data['village'] = null;
        $data['note'] = $data['note'] ?: null;

        if ($this->removePhoto && ! $this->photo) {
            Storage::disk('public')->delete($this->student->photo_path);
            $data['photo_path'] = null;
        }

        if ($this->photo) {
            $data['photo_path'] = $this->storePhoto($this->photo, $this->student->photo_path);
        }

        $this->student->update($data);

        $this->syncPrimaryGuardian($this->student, $guardianId, $guardianRelationship);
        $this->syncClassHistory($this->student, $data);

        session()->flash('status', 'اطلاعات شاگرد با موفقیت به روز شد.');

        return redirect()->route('student-index');
    }

    protected function rules(): array
    {
        return [
            'form.asas_number' => ['required', 'string', 'max:255', Rule::unique('students', 'asas_number')->ignore($this->student->id)],
            'form.guardian_id' => ['nullable', 'exists:guardians,id'],
            'form.guardian_relationship' => ['nullable', 'string', 'max:100'],
            'form.custom_guardian_relationship' => [
                Rule::requiredIf(fn () => filled($this->form['guardian_id'] ?? null) && ($this->form['guardian_relationship'] ?? null) === '__custom'),
                'nullable',
                'string',
                'max:100',
            ],
            'form.first_name' => ['required', 'string', 'max:255'],
            'form.last_name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['required', 'string', 'max:255'],
            'form.grandfather_name' => ['required', 'string', 'max:255'],
            'form.tazkira_number' => ['required', 'string', 'max:255', Rule::unique('students', 'tazkira_number')->ignore($this->student->id)],
            'form.gender' => ['required', Rule::in(array_column(StudentGender::cases(), 'value'))],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.student_type' => ['required', Rule::in(['new', 'transferred'])],
            'form.previous_school' => ['nullable', 'string', 'max:255'],
            'form.class_id' => ['required', 'exists:classes,id'],
            'form.section_id' => ['required', Rule::exists('sections', 'id')->where(fn ($query) => $query->where('class_id', $this->form['class_id'] ?? null))],
            'form.academic_year_id' => ['required', 'exists:academic_years,id'],
            'form.admission_date' => ['required', 'date'],
            'form.status' => ['required', Rule::in(array_column(StudentStatus::cases(), 'value'))],
            'form.note' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'removePhoto' => ['boolean'],
        ];
    }

    protected function storePhoto($photo, ?string $existingPath = null): string
    {
        if ($existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        return $photo->store('student_photos', 'public');
    }

    protected function syncPrimaryGuardian(Model $student, int|string|null $guardianId, ?string $relationship): void
    {
        if (! $guardianId) {
            return;
        }

        $student->guardians()->syncWithoutDetaching([
            $guardianId => [
                'is_primary' => true,
                'relationship' => $relationship ?: GuardianRelationship::Guardian->value,
            ],
        ]);
    }

    protected function syncClassHistory(Model $student, array $data): void
    {
        $academicYear = AcademicYear::query()->find($data['academic_year_id'] ?? null);

        if (! $academicYear) {
            return;
        }

        $student->classHistory()->syncWithoutDetaching([
            $data['class_id'] => [
                'academic_year' => (string) $academicYear->name,
                'status' => 'active',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.pages.student.student-edit', $this->viewData())->layout('layouts.app', [
            'title' => 'ویرایش شاگرد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'شاگردان', 'url' => route('student-index')],
                ['label' => 'ویرایش شاگرد'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classOptions' => OptionLists::academicClasses(),
            'sectionOptions' => OptionLists::sections($this->form['class_id'] ?? null),
            'academicYearOptions' => OptionLists::academicYears(),
            'guardianOptions' => OptionLists::guardians($this->guardianSearch),
            'guardianRelationshipOptions' => OptionLists::guardianRelationships(),
            'genderOptions' => OptionLists::studentGenders(),
            'statusOptions' => OptionLists::studentStatuses(),
            'studentTypeOptions' => OptionLists::studentTypes(),
        ];
    }
}
