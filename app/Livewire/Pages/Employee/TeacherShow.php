<?php

namespace App\Livewire\Pages\Employee;

use App\Models\Employee;
use Livewire\Component;

class TeacherShow extends Component
{
    public Employee $teacher;

    public function mount(Employee $teacher): void
    {
        $this->teacher = $teacher->load(['manager']);
    }

    public function render()
    {
        return view('livewire.pages.employee.teacher-show')->layout('layouts.app', [
            'title' => 'مشاهده استاد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'استادان', 'url' => route('teachers.index')],
                ['label' => $this->teacher->name ?: $this->teacher->employee_id],
            ],
        ]);
    }
}
