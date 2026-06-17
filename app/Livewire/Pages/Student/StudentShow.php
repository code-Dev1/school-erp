<?php

namespace App\Livewire\Pages\Student;

use App\Models\Student;
use Livewire\Component;

class StudentShow extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $this->student = $student->load(['academicClass', 'section', 'academicYear', 'primaryGuardian']);
    }

    public function render()
    {
        return view('livewire.pages.student.student-show')->layout('layouts.app', [
            'title' => 'مشاهده شاگرد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'شاگردان', 'url' => route('student-index')],
                ['label' => $this->student->name ?: $this->student->asas_number],
            ],
        ]);
    }
}
