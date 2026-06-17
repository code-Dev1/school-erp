<?php

namespace App\Livewire\Pages\Employee;

use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Students\StudentGender;
use App\Models\Employee;
use App\Support\School\EmployeeData;
use App\Support\School\OptionLists;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class TeacherEdit extends Component
{
    use WithFileUploads;

    public Employee $teacher;
    public $photo = null;
    public bool $removePhoto = false;

    public array $form = [
        'first_name' => '',
        'last_name' => '',
        'father_name' => '',
        'grandfather_name' => '',
        'tazkira_number' => '',
        'gender' => '',
        'date_of_birth' => '',
        'phone' => '',
        'whatsapp_number' => '',
        'email' => '',
        'teacher_type' => 'full_time',
        'job_title' => 'teacher',
        'department' => 'education',
        'education_level' => '',
        'field_of_study' => '',
        'hired_at' => '',
        'contract_type' => 'permanent',
        'base_salary' => '0',
        'status' => 'active',
        'note' => '',
    ];

    public function mount(Employee $teacher): void
    {
        $this->teacher = $teacher;
        $this->form = [
            'first_name' => $teacher->first_name,
            'last_name' => $teacher->last_name,
            'father_name' => $teacher->father_name,
            'grandfather_name' => $teacher->grandfather_name,
            'tazkira_number' => $teacher->tazkira_number,
            'gender' => $teacher->gender?->value ?? '',
            'date_of_birth' => $teacher->date_of_birth?->format('Y-m-d'),
            'phone' => $teacher->phone,
            'whatsapp_number' => $teacher->whatsapp_number,
            'email' => $teacher->email,
            'teacher_type' => $teacher->teacher_type ?? 'full_time',
            'job_title' => $teacher->job_title ?? 'teacher',
            'department' => $teacher->department ?? 'education',
            'education_level' => $teacher->education_level,
            'field_of_study' => $teacher->field_of_study,
            'hired_at' => $teacher->hired_at?->format('Y-m-d'),
            'contract_type' => $teacher->contract_type?->value ?? ContractType::Permanent->value,
            'base_salary' => $teacher->base_salary ?? '0',
            'status' => $teacher->status?->value ?? EmployeeStatus::Active->value,
            'note' => $teacher->note,
        ];
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        $data = collect($validated)
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();

        $data = EmployeeData::fromForm($data, EmployeeType::Teacher);

        if ($this->photo) {
            $data['photo_path'] = $this->storePhoto($this->photo, $this->teacher->photo_path);
        }

        if ($this->removePhoto && ! $this->photo) {
            Storage::disk('public')->delete($this->teacher->photo_path);
            $data['photo_path'] = null;
        }

        $this->teacher->update($data);

        session()->flash('status', 'اطلاعات استاد با موفقیت به‌روزرسانی شد.');

        return redirect()->route('teachers.index');
    }

    protected function rules(): array
    {
        return [
            'form.first_name' => ['required', 'string', 'max:255'],
            'form.last_name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['nullable', 'string', 'max:255'],
            'form.grandfather_name' => ['nullable', 'string', 'max:255'],
            'form.tazkira_number' => ['nullable', 'string', 'max:255', Rule::unique('employees', 'tazkira_number')->ignore($this->teacher->id)],
            'form.gender' => ['nullable', Rule::in(array_column(StudentGender::cases(), 'value'))],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.teacher_type' => ['nullable', 'string', 'max:255'],
            'form.job_title' => ['required', 'string', 'max:255'],
            'form.department' => ['nullable', 'string', 'max:255'],
            'form.education_level' => ['nullable', 'string', 'max:255'],
            'form.field_of_study' => ['nullable', 'string', 'max:255'],
            'form.hired_at' => ['required', 'date'],
            'form.contract_type' => ['required', Rule::in(array_column(ContractType::cases(), 'value'))],
            'form.base_salary' => ['nullable', 'numeric', 'min:0'],
            'form.status' => ['required', Rule::in(array_column(EmployeeStatus::cases(), 'value'))],
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

        return $photo->store('employee_photos', 'public');
    }

    public function render()
    {
        return view('livewire.pages.employee.teacher-edit', $this->viewData())->layout('layouts.app', [
            'title' => 'ویرایش استاد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'استادان', 'url' => route('teachers.index')],
                ['label' => 'ویرایش استاد'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'genderOptions' => OptionLists::studentGenders(),
            'teacherTypeOptions' => OptionLists::teacherTypes(),
            'contractTypeOptions' => OptionLists::contractTypes(),
            'statusOptions' => OptionLists::employeeStatuses(),
        ];
    }
}
