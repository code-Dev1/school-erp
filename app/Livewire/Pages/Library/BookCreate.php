<?php

namespace App\Livewire\Pages\Library;

use App\Models\LibraryBook;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookCreate extends Component
{
    public array $form = [
        'title' => '',
        'author' => '',
        'isbn' => '',
        'category' => '',
        'total_copies' => '1',
        'available_copies' => '1',
        'shelf' => '',
        'status' => 'available',
        'note' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        LibraryBook::create([
            'title' => $validated['title'],
            'author' => $validated['author'] ?: null,
            'isbn' => $validated['isbn'] ?: null,
            'category' => $validated['category'] ?: null,
            'total_copies' => $validated['total_copies'],
            'available_copies' => $validated['available_copies'],
            'shelf' => $validated['shelf'] ?: null,
            'status' => $validated['status'],
            'note' => $validated['note'] ?: null,
        ]);

        session()->flash('status', 'کتاب ثبت شد.');

        return redirect()->route('library.books.index');
    }

    protected function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'max:255'],
            'form.author' => ['nullable', 'string', 'max:255'],
            'form.isbn' => ['nullable', 'string', 'max:255', Rule::unique('library_books', 'isbn')],
            'form.category' => ['nullable', 'string', 'max:255'],
            'form.total_copies' => ['required', 'integer', 'min:1'],
            'form.available_copies' => ['required', 'integer', 'min:0', 'lte:form.total_copies'],
            'form.shelf' => ['nullable', 'string', 'max:255'],
            'form.status' => ['required', Rule::in(['available', 'unavailable'])],
            'form.note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.library.book-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت کتاب',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کتاب ها', 'url' => route('library.books.index')],
                ['label' => 'ثبت کتاب'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'statusOptions' => OptionLists::libraryStatuses(),
        ];
    }
}
