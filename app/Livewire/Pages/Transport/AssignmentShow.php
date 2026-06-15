<?php

namespace App\Livewire\Pages\Transport;

use App\Models\StudentTransport;
use Livewire\Component;

class AssignmentShow extends Component
{
    public StudentTransport $assignment;

    public function mount(StudentTransport $assignment): void
    {
        $this->assignment = $assignment->load(['student', 'transportService', 'academicYear']);
    }

    public function render()
    {
        return view('livewire.pages.transport.assignment-show')->layout('layouts.app', [
            'title' => 'جزئیات ترانسپورت شاگرد',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت شاگردان', 'url' => route('transport.assignments.index')],
                ['label' => 'جزئیات'],
            ],
        ]);
    }
}
