<?php

namespace App\Livewire\Pages\Sales;

use App\Models\SaleItem;
use Livewire\Component;

class ItemShow extends Component
{
    public SaleItem $item;

    public function mount(SaleItem $item): void
    {
        $this->item = $item;
    }

    public function render()
    {
        return view('livewire.pages.sales.item-show')->layout('layouts.app', [
            'title' => 'Sale item details',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Sale items', 'url' => route('sales.items.index')],
                ['label' => $this->item->name],
            ],
        ]);
    }
}
