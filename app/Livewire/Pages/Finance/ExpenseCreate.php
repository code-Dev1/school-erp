<?php

namespace App\Livewire\Pages\Finance;

use App\Models\Expense;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExpenseCreate extends Component
{
    public array $form = [
        'title' => '',
        'category' => '',
        'amount' => '',
        'date' => '',
        'paid_by' => '',
        'description' => '',
        'notes' => '',
    ];

    public function mount(): void
    {
        $this->form['date'] = now()->format('Y-m-d');
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        Expense::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'expense_date' => $validated['date'],
            'paid_by' => $validated['paid_by'] ?: null,
            'description' => $validated['description'] ?: null,
            'notes' => $validated['notes'] ?: null,
            'recorded_by' => auth()->id(),
        ]);

        session()->flash('status', 'مصرف ثبت شد.');

        return redirect()->route('expenses.index');
    }

    protected function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.category' => ['required', Rule::in(array_keys(OptionLists::expenseCategories()))],
            'form.amount' => ['required', 'numeric', 'min:0'],
            'form.date' => ['required', 'date'],
            'form.paid_by' => ['nullable', 'string', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.notes' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.finance.expense-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت مصرف',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مصارف روزانه', 'url' => route('expenses.index')],
                ['label' => 'ثبت مصرف'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'categoryOptions' => OptionLists::expenseCategories(),
        ];
    }
}
