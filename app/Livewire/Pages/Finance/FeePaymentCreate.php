<?php

namespace App\Livewire\Pages\Finance;

use App\Enums\Finance\FeePaymentStatus;
use App\Services\Finance\RecordFeePayment;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FeePaymentCreate extends Component
{
    public array $form = [
        'student_id' => '',
        'fee_structure_id' => '',
        'academic_year_id' => '',
        'amount' => '',
        'amount_paid' => '',
        'discount_amount' => '0',
        'due_date' => '',
        'payment_date' => '',
        'covers_from' => '',
        'covers_to' => '',
        'months_count' => '1',
        'status' => 'paid',
        'receipt_number' => '',
        'note' => '',
    ];

    public function mount(): void
    {
        $this->form['payment_date'] = now()->format('Y-m-d');
        $this->form['receipt_number'] = 'REC-'.now()->format('YmdHis');
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        app(RecordFeePayment::class)->create([
            'student_id' => $validated['student_id'],
            'fee_structure_id' => $validated['fee_structure_id'],
            'academic_year_id' => $validated['academic_year_id'] ?: null,
            'amount' => $validated['amount'] ?: null,
            'amount_paid' => $validated['amount_paid'],
            'discount_amount' => $validated['discount_amount'],
            'due_date' => $validated['due_date'] ?: null,
            'payment_date' => $validated['payment_date'],
            'covers_from' => $validated['covers_from'] ?: null,
            'covers_to' => $validated['covers_to'] ?: null,
            'months_count' => $validated['months_count'],
            'status' => $validated['status'],
            'receipt_number' => $validated['receipt_number'],
            'recorded_by' => auth()->id(),
            'note' => $validated['note'] ?: null,
        ]);

        session()->flash('status', 'پرداخت فیس ثبت شد.');

        return redirect()->route('fees.index');
    }

    protected function rules(): array
    {
        return [
            'form.student_id' => ['required', 'exists:students,id'],
            'form.fee_structure_id' => ['required', 'exists:fee_structures,id'],
            'form.academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'form.amount' => ['nullable', 'numeric', 'min:0'],
            'form.amount_paid' => ['required', 'numeric', 'min:0'],
            'form.discount_amount' => ['nullable', 'numeric', 'min:0'],
            'form.due_date' => ['nullable', 'date'],
            'form.payment_date' => ['required', 'date'],
            'form.covers_from' => ['nullable', 'date'],
            'form.covers_to' => ['nullable', 'date', 'after_or_equal:form.covers_from'],
            'form.months_count' => ['required', 'integer', 'min:1', 'max:12'],
            'form.status' => ['required', Rule::in(array_column(FeePaymentStatus::cases(), 'value'))],
            'form.receipt_number' => ['required', 'string', 'max:255', Rule::unique('fee_payments', 'receipt_number')],
            'form.note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.finance.fee-payment-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت پرداخت فیس',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'فیس شاگردان', 'url' => route('fees.index')],
                ['label' => 'ثبت پرداخت فیس'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'studentOptions' => OptionLists::students(),
            'feeStructureOptions' => OptionLists::feeStructures(),
            'academicYearOptions' => OptionLists::academicYears(),
            'statusOptions' => OptionLists::feePaymentStatuses(),
        ];
    }
}
