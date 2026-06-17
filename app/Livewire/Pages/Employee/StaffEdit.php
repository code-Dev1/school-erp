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

class StaffEdit extends Component
{
    use WithFileUploads;

    public Employee $staff;
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
        'job_title' => '',
        'custom_job_title' => '',
        'department' => '',
        'reports_to' => '',
        'hired_at' => '',
        'contract_type' => 'permanent',
        'base_salary' => '0',
        'status' => 'active',
        'note' => '',
    ];

    public function mount(Employee $staff): void
    {
        $this->staff = $staff;
        $this->form = [
            'first_name' => $staff->first_name,
            'last_name' => $staff->last_name,
            'father_name' => $staff->father_name,
            'grandfather_name' => $staff->grandfather_name,
            'tazkira_number' => $staff->tazkira_number,
            'gender' => $staff->gender?->value ?? '',
            'date_of_birth' => $staff->date_of_birth?->format('Y-m-d'),
            'phone' => $staff->phone,
            'whatsapp_number' => $staff->whatsapp_number,
            'email' => $staff->email,
            'job_title' => $staff->job_title,
            'custom_job_title' => '',
            'department' => $staff->department,
            'reports_to' => $staff->reports_to,
            'hired_at' => $staff->hired_at?->format('Y-m-d'),
            'contract_type' => $staff->contract_type?->value ?? ContractType::Permanent->value,
            'base_salary' => $staff->base_salary ?? '0',
            'status' => $staff->status?->value ?? EmployeeStatus::Active->value,
            'note' => $staff->note,
        ];
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        $data = collect($validated)
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();

        $data = EmployeeData::fromForm($data, EmployeeType::Staff);

        if ($this->photo) {
            $data['photo_path'] = $this->storePhoto($this->photo, $this->staff->photo_path);
        }

        if ($this->removePhoto && ! $this->photo) {
            Storage::disk('public')->delete($this->staff->photo_path);
            $data['photo_path'] = null;
        }

        $this->staff->update($data);

        session()->flash('status', 'اطلاعات کارمند با موفقیت به‌روزرسانی شد.');

        return redirect()->route('staff.index');
    }

    protected function rules(): array
    {
        return [
            'form.first_name' => ['required', 'string', 'max:255'],
            'form.last_name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['nullable', 'string', 'max:255'],
            'form.grandfather_name' => ['nullable', 'string', 'max:255'],
            'form.tazkira_number' => ['nullable', 'string', 'max:255', Rule::unique('employees', 'tazkira_number')->ignore($this->staff->id)],
            'form.gender' => ['nullable', Rule::in(array_column(StudentGender::cases(), 'value'))],
            'form.date_of_birth' => ['nullable', 'date'],
            'form.phone' => ['nullable', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.job_title' => ['required', 'string', 'max:255'],
            'form.custom_job_title' => [
                Rule::requiredIf(fn () => ($this->form['job_title'] ?? null) === '__custom'),
                'nullable',
                'string',
                'max:255',
            ],
            'form.department' => ['nullable', 'string', 'max:255'],
            'form.reports_to' => ['nullable', 'exists:employees,id'],
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
        return view('livewire.pages.employee.staff-edit', $this->viewData())->layout('layouts.app', [
            'title' => 'ویرایش کارمند',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کارمندان', 'url' => route('staff.index')],
                ['label' => 'ویرایش کارمند'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'genderOptions' => OptionLists::studentGenders(),
            'jobTitleOptions' => OptionLists::staffJobTitles(),
            'managerOptions' => OptionLists::managers(),
            'contractTypeOptions' => OptionLists::contractTypes(),
            'statusOptions' => OptionLists::employeeStatuses(),
        ];
    }
}
