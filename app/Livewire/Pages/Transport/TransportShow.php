<?php

namespace App\Livewire\Pages\Transport;

use App\Models\TransportService;
use Livewire\Component;

class TransportShow extends Component
{
    public TransportService $transportService;

    public function mount(TransportService $transportService): void
    {
        $this->transportService = $transportService->load(['assignments.student', 'assignments.academicYear']);
    }

    public function render()
    {
        return view('livewire.pages.transport.transport-show')->layout('layouts.app', [
            'title' => 'جزئیات ترانسپورت',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت', 'url' => route('transport.index')],
                ['label' => $this->transportService->vehicle_plate_number],
            ],
        ]);
    }
}
