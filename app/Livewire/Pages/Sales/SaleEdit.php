<?php

namespace App\Livewire\Pages\Sales;

use App\Models\StudentSale;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SaleEdit extends Component
{
    public StudentSale $sale;

    public array $form = [
        'student_id' => '',
        'sold_at' => '',
        'discount_amount' => '0',
        'paid_amount' => '0',
        'status' => 'paid',
        'note' => '',
    ];

    public function mount(StudentSale $sale): void
    {
        $this->sale = $sale;
        $this->form = [
            'student_id' => (string) $sale->student_id,
            'sold_at' => $sale->sold_at?->format('Y-m-d'),
            'discount_amount' => (string) $sale->discount_amount,
            'paid_amount' => (string) $sale->paid_amount,
            'status' => $sale->status,
            'note' => $sale->note,
        ];
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $total = max(0, (float) $this->sale->subtotal - (float) $validated['discount_amount']);
        $paid = (float) $validated['paid_amount'];

        $this->sale->update([
            'student_id' => $validated['student_id'] ?: null,
            'sold_at' => $validated['sold_at'],
            'discount_amount' => $validated['discount_amount'],
            'total_amount' => $total,
            'paid_amount' => $paid,
            'balance_amount' => max(0, $total - $paid),
            'status' => $validated['status'],
            'note' => $validated['note'] ?: null,
        ]);

        session()->flash('status', 'Sale updated.');

        return redirect()->route('sales.index');
    }

    protected function rules(): array
    {
        return [
            'form.student_id' => ['nullable', 'exists:students,id'],
            'form.sold_at' => ['required', 'date'],
            'form.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'form.paid_amount' => ['required', 'numeric', 'min:0'],
            'form.status' => ['required', Rule::in(array_keys(OptionLists::saleStatuses()))],
            'form.note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.sales.sale-edit', $this->viewData())->layout('layouts.app', [
            'title' => 'Edit sale',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Student sales', 'url' => route('sales.index')],
                ['label' => $this->sale->invoice_number],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'studentOptions' => OptionLists::students(),
            'statusOptions' => OptionLists::saleStatuses(),
        ];
    }
}
