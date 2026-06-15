<?php

namespace App\Livewire\Pages\Library;

use App\Models\LibraryBook;
use App\Support\School\OptionLists;
use Livewire\Component;
use Livewire\WithPagination;

class BookIndex extends Component
{
    use WithPagination;

    public function delete(int $bookId): void
    {
        LibraryBook::query()->findOrFail($bookId)->delete();

        session()->flash('status', 'کتاب حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.library.book-index', $this->viewData())->layout('layouts.app', [
            'title' => 'کتاب ها',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کتاب ها'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'books' => LibraryBook::query()->withCount('loans')->latest('id')->paginate(12),
            'statusOptions' => OptionLists::libraryStatuses(),
        ];
    }
}
