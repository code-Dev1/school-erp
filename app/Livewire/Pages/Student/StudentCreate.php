<?php

namespace App\Livewire\Pages\Student;

use App\Enums\Students\GuardianRelationship;
use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;

class StudentCreate extends Component
{
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

    public function mount(): void
    {
        $this->form['asas_number'] = Student::generateAsasNumber();
        $this->form['admission_date'] = now()->format('Y-m-d');
        $this->form['status'] = StudentStatus::Active->value;
    }

    protected $listeners = [
        'guardianChanged' => 'setGuardian',
    ];

    public function setGuardian($id)
    {
        $this->form['guardian_id'] = $id ?: null;
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        $data = collect($validated)
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

        $student = Student::create($data);

        $this->syncPrimaryGuardian($student, $guardianId, $guardianRelationship);
        $this->syncClassHistory($student, $data);

        session()->flash('status', 'شاگرد با موفقیت ثبت شد.');

        return redirect()->route('student-index');
    }

    protected function rules(): array
    {
        return [
            'form.asas_number' => ['required', 'string', 'max:255', Rule::unique('students', 'asas_number')],
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
            'form.tazkira_number' => ['required', 'string', 'max:255', Rule::unique('students', 'tazkira_number')],
            'form.gender' => ['required', Rule::in(array_column(StudentGender::cases(), 'value'))],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.student_type' => ['required', Rule::in(['new', 'transferred'])],
            'form.previous_school' => ['nullable', 'string', 'max:255'],
            'form.class_id' => ['required', 'exists:classes,id'],
            'form.section_id' => ['required', 'exists:sections,id'],
            'form.academic_year_id' => ['required', 'exists:academic_years,id'],
            'form.admission_date' => ['required', 'date'],
            'form.status' => ['required', Rule::in(array_column(StudentStatus::cases(), 'value'))],
            'form.note' => ['nullable', 'string'],
        ];
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
        $guardianOptions = Guardian::query()
            ->when($this->guardianSearch !== '', function ($query): void {
                $search = trim($this->guardianSearch);

                $query->where(function ($query) use ($search): void {
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
            ->mapWithKeys(function (Guardian $guardian): array {
                $name = $guardian->name ?: trim($guardian->first_name.' '.$guardian->last_name);
                $phone = $guardian->phone ?: $guardian->contact_number;
                $label = trim(($name ?: 'سرپرست #'.$guardian->id).($phone ? ' - '.$phone : ''));

                return [$guardian->id => $label];
            })
            ->all();

        return view('livewire.pages.student.student-create', [
            'classOptions' => AcademicClass::query()->orderBy('name')->pluck('name', 'id')->all(),
            'sectionOptions' => Section::query()->orderBy('name')->pluck('name', 'id')->all(),
            'academicYearOptions' => AcademicYear::query()->orderByDesc('id')->pluck('name', 'id')->all(),
            'guardianOptions' => $guardianOptions,
            'guardianRelationshipOptions' => [
                GuardianRelationship::Father->value => 'پدر',
                GuardianRelationship::Mother->value => 'مادر',
                GuardianRelationship::Uncle->value => 'کاکا',
                GuardianRelationship::Brother->value => 'برادر',
                GuardianRelationship::Guardian->value => 'سرپرست',
                '__custom' => 'دیگر',
            ],
            'genderOptions' => [
                StudentGender::Male->value => 'ذکور',
                StudentGender::Female->value => 'اناث',
            ],
            'statusOptions' => [
                StudentStatus::Active->value => 'فعال',
                StudentStatus::Transferred->value => 'تبدیل شده',
                StudentStatus::Graduated->value => 'فارغ',
                StudentStatus::Expelled->value => 'اخراج شده',
            ],
            'studentTypeOptions' => [
                'new' => 'جدید',
                'transferred' => 'تبدیلی',
            ],
        ])->layout('layouts.app', [
            'title' => 'ثبت نام شاگرد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'شاگردان', 'url' => route('student-index')],
                ['label' => 'ثبت نام'],
            ],
        ]);
    }
}
