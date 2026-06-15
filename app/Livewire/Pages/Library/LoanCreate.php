<?php

namespace App\Livewire\Pages\Library;

use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LoanCreate extends Component
{
    public array $form = [
        'library_book_id' => '',
        'borrower_type' => 'student',
        'student_id' => '',
        'employee_id' => '',
        'borrowed_at' => '',
        'due_at' => '',
        'status' => 'borrowed',
        'note' => '',
    ];

    public function mount(): void
    {
        $this->form['borrowed_at'] = now()->format('Y-m-d');
        $this->form['due_at'] = now()->addDays(14)->format('Y-m-d');
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $book = LibraryBook::query()->findOrFail($validated['library_book_id']);

        LibraryLoan::create([
            'library_book_id' => $book->id,
            'student_id' => $validated['borrower_type'] === 'student' ? $validated['student_id'] : null,
            'employee_id' => $validated['borrower_type'] === 'employee' ? $validated['employee_id'] : null,
            'borrowed_at' => $validated['borrowed_at'],
            'due_at' => $validated['due_at'],
            'status' => $validated['status'],
            'note' => $validated['note'] ?: null,
            'recorded_by' => auth()->id(),
        ]);

        if ($validated['status'] === 'borrowed' && $book->available_copies > 0) {
            $book->decrement('available_copies');
        }

        session()->flash('status', 'امانت کتاب ثبت شد.');

        return redirect()->route('library.loans.index');
    }

    protected function rules(): array
    {
        return [
            'form.library_book_id' => ['required', 'exists:library_books,id'],
            'form.borrower_type' => ['required', Rule::in(['student', 'employee'])],
            'form.student_id' => [Rule::requiredIf(fn () => ($this->form['borrower_type'] ?? null) === 'student'), 'nullable', 'exists:students,id'],
            'form.employee_id' => [Rule::requiredIf(fn () => ($this->form['borrower_type'] ?? null) === 'employee'), 'nullable', 'exists:employees,id'],
            'form.borrowed_at' => ['required', 'date'],
            'form.due_at' => ['required', 'date', 'after_or_equal:form.borrowed_at'],
            'form.status' => ['required', Rule::in(['borrowed', 'returned', 'overdue'])],
            'form.note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.library.loan-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت امانت کتاب',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'امانت کتاب', 'url' => route('library.loans.index')],
                ['label' => 'ثبت امانت'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'bookOptions' => OptionLists::libraryBooks(),
            'studentOptions' => OptionLists::students(),
            'employeeOptions' => OptionLists::allEmployees(),
            'statusOptions' => OptionLists::loanStatuses(),
            'borrowerTypeOptions' => [
                'student' => 'شاگرد',
                'employee' => 'کارمند',
            ],
        ];
    }
}
