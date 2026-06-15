<?php

namespace App\Livewire\Pages\Finance;

use App\Models\Employee;
use App\Models\PayrollRecord;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PayrollCreate extends Component
{
    public array $form = [
        'employee_id' => '',
        'month' => '',
        'year' => '',
        'base_salary' => '',
        'total_allowances' => '0',
        'total_deductions' => '0',
        'absence_deduction' => '0',
        'paid_at' => '',
    ];

    public function mount(): void
    {
        $this->form['month'] = (string) now()->month;
        $this->form['year'] = (string) now()->year;
    }

    public function updatedFormEmployeeId($employeeId): void
    {
        $employee = Employee::query()->find($employeeId);

        if ($employee) {
            $this->form['base_salary'] = (string) ($employee->salary ?: $employee->base_salary);
        }
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $netSalary = (float) $validated['base_salary'] + (float) $validated['total_allowances'] - (float) $validated['total_deductions'] - (float) $validated['absence_deduction'];

        PayrollRecord::create([
            'employee_id' => $validated['employee_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'base_salary' => $validated['base_salary'],
            'total_allowances' => $validated['total_allowances'],
            'total_deductions' => $validated['total_deductions'],
            'absence_deduction' => $validated['absence_deduction'],
            'net_salary' => max($netSalary, 0),
            'paid_at' => $validated['paid_at'] ?: null,
            'recorded_by' => auth()->id(),
        ]);

        session()->flash('status', 'معاش ثبت شد.');

        return redirect()->route('payroll.index');
    }

    protected function rules(): array
    {
        return [
            'form.employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('payroll_records', 'employee_id')->where(fn ($query) => $query
                    ->where('month', $this->form['month'])
                    ->where('year', $this->form['year'])),
            ],
            'form.month' => ['required', 'integer', 'min:1', 'max:12'],
            'form.year' => ['required', 'integer', 'min:1300', 'max:2200'],
            'form.base_salary' => ['required', 'numeric', 'min:0'],
            'form.total_allowances' => ['nullable', 'numeric', 'min:0'],
            'form.total_deductions' => ['nullable', 'numeric', 'min:0'],
            'form.absence_deduction' => ['nullable', 'numeric', 'min:0'],
            'form.paid_at' => ['nullable', 'date'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.finance.payroll-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت معاش',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'معاشات', 'url' => route('payroll.index')],
                ['label' => 'ثبت معاش'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'employeeOptions' => OptionLists::allEmployees(),
            'monthOptions' => OptionLists::months(),
        ];
    }
}
