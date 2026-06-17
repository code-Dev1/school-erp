<?php

namespace App\Livewire\Pages\Employee;

use App\Models\Employee;
use Livewire\Component;

class StaffShow extends Component
{
    public Employee $staff;

    public function mount(Employee $staff): void
    {
        $this->staff = $staff->load(['manager']);
    }

    public function render()
    {
        return view('livewire.pages.employee.staff-show')->layout('layouts.app', [
            'title' => 'مشاهده کارمند',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کارمندان', 'url' => route('staff.index')],
                ['label' => $this->staff->name ?: $this->staff->employee_id],
            ],
        ]);
    }
}
