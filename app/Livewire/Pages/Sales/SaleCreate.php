<?php

namespace App\Livewire\Pages\Sales;

use App\Services\Sales\CreateStudentSale;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaleCreate extends Component
{
    public array $form = [
        'student_id' => '',
        'invoice_number' => '',
        'sold_at' => '',
        'sale_item_id' => '',
        'quantity' => '1',
        'discount_amount' => '0',
        'paid_amount' => '',
        'status' => 'paid',
        'note' => '',
    ];

    public function mount(): void
    {
        $this->form['sold_at'] = now()->format('Y-m-d');
        $this->form['invoice_number'] = 'INV-'.now()->format('YmdHis');
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $validated['student_id'] = $validated['student_id'] ?: null;
        $validated['paid_amount'] = $validated['paid_amount'] === '' ? null : $validated['paid_amount'];
        $validated['recorded_by'] = auth()->id();

        app(CreateStudentSale::class)->create($validated, [[
            'sale_item_id' => $validated['sale_item_id'],
            'quantity' => $validated['quantity'],
        ]]);

        session()->flash('status', 'Sale saved.');

        return redirect()->route('sales.index');
    }

    protected function rules(): array
    {
        return [
            'form.student_id' => ['nullable', 'exists:students,id'],
            'form.invoice_number' => ['required', 'string', 'max:255', Rule::unique('student_sales', 'invoice_number')],
            'form.sold_at' => ['required', 'date'],
            'form.sale_item_id' => ['required', 'exists:sale_items,id'],
            'form.quantity' => ['required', 'integer', 'min:1'],
            'form.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'form.paid_amount' => ['nullable', 'numeric', 'min:0'],
            'form.status' => ['required', Rule::in(array_keys(OptionLists::saleStatuses()))],
            'form.note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.sales.sale-create', $this->viewData())->layout('layouts.app', [
            'title' => 'Create sale',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Student sales', 'url' => route('sales.index')],
                ['label' => 'Create sale'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'studentOptions' => OptionLists::students(),
            'itemOptions' => OptionLists::saleItems(),
            'statusOptions' => OptionLists::saleStatuses(),
        ];
    }
}
