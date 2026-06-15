<?php

namespace App\Livewire\Pages\Sales;

use App\Models\SaleItem;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ItemForm extends Component
{
    public ?SaleItem $item = null;

    public array $form = [
        'sku' => '',
        'name' => '',
        'category' => 'book',
        'unit_price' => '',
        'stock_quantity' => '0',
        'reorder_level' => '0',
        'status' => 'active',
        'description' => '',
    ];

    public function mount(?SaleItem $item = null): void
    {
        $this->item = $item?->exists ? $item : null;

        if ($this->item) {
            $this->form = [
                'sku' => $this->item->sku,
                'name' => $this->item->name,
                'category' => $this->item->category,
                'unit_price' => (string) $this->item->unit_price,
                'stock_quantity' => (string) $this->item->stock_quantity,
                'reorder_level' => (string) $this->item->reorder_level,
                'status' => $this->item->status,
                'description' => $this->item->description,
            ];
        }
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        SaleItem::updateOrCreate(['id' => $this->item?->id], $validated);

        session()->flash('status', 'Sale item saved.');

        return redirect()->route('sales.items.index');
    }

    protected function rules(): array
    {
        return [
            'form.sku' => ['required', 'string', 'max:255', Rule::unique('sale_items', 'sku')->ignore($this->item?->id)],
            'form.name' => ['required', 'string', 'max:255'],
            'form.category' => ['required', Rule::in(array_keys(OptionLists::saleCategories()))],
            'form.unit_price' => ['required', 'numeric', 'min:0'],
            'form.stock_quantity' => ['required', 'integer', 'min:0'],
            'form.reorder_level' => ['required', 'integer', 'min:0'],
            'form.status' => ['required', Rule::in(array_keys(OptionLists::activeStatuses()))],
            'form.description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        $title = $this->item ? 'Edit sale item' : 'Create sale item';

        return view('livewire.pages.sales.item-form', $this->viewData())->layout('layouts.app', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Sale items', 'url' => route('sales.items.index')],
                ['label' => $title],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'title' => $this->item ? 'Edit sale item' : 'Create sale item',
            'categoryOptions' => OptionLists::saleCategories(),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
