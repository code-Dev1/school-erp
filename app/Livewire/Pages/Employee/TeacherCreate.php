<?php

namespace App\Livewire\Pages\Employee;

use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Students\StudentGender;
use App\Models\Employee;
use App\Support\School\EmployeeData;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TeacherCreate extends Component
{
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

    public function mount(): void
    {
        $this->form['hired_at'] = now()->format('Y-m-d');
        $this->form['contract_type'] = ContractType::Permanent->value;
        $this->form['status'] = EmployeeStatus::Active->value;
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        Employee::create(EmployeeData::fromForm($validated, EmployeeType::Teacher));

        session()->flash('status', 'استاد با موفقیت ثبت شد.');

        return redirect()->route('teachers.index');
    }

    protected function rules(): array
    {
        return [
            'form.first_name' => ['required', 'string', 'max:255'],
            'form.last_name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['nullable', 'string', 'max:255'],
            'form.grandfather_name' => ['nullable', 'string', 'max:255'],
            'form.tazkira_number' => ['nullable', 'string', 'max:255', Rule::unique('employees', 'tazkira_number')],
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
        ];
    }

    public function render()
    {
        return view('livewire.pages.employee.teacher-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت استاد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'استادان', 'url' => route('teachers.index')],
                ['label' => 'ثبت استاد'],
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
