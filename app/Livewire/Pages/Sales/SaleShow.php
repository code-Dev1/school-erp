<?php

namespace App\Livewire\Pages\Sales;

use App\Models\StudentSale;
use Livewire\Component;

class SaleShow extends Component
{
    public StudentSale $sale;

    public function mount(StudentSale $sale): void
    {
        $this->sale = $sale->load(['student', 'lines.item', 'recordedBy']);
    }

    public function render()
    {
        return view('livewire.pages.sales.sale-show')->layout('layouts.app', [
            'title' => 'Sale receipt',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Student sales', 'url' => route('sales.index')],
                ['label' => $this->sale->invoice_number],
            ],
        ]);
    }
}
