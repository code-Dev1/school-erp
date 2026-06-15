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

class StaffCreate extends Component
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

    public function mount(): void
    {
        $this->form['hired_at'] = now()->format('Y-m-d');
        $this->form['contract_type'] = ContractType::Permanent->value;
        $this->form['status'] = EmployeeStatus::Active->value;
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        Employee::create(EmployeeData::fromForm($validated, EmployeeType::Staff));

        session()->flash('status', 'کارمند با موفقیت ثبت شد.');

        return redirect()->route('staff.index');
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
        ];
    }

    public function render()
    {
        return view('livewire.pages.employee.staff-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت کارمند',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کارمندان', 'url' => route('staff.index')],
                ['label' => 'ثبت کارمند'],
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
