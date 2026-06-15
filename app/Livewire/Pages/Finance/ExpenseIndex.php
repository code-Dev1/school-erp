<?php

namespace App\Livewire\Pages\Finance;

use App\Models\Expense;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $category = '';

    public function clearFilters(): void
    {
        $this->reset('category');
        $this->resetPage();
    }

    public function delete(int $expenseId): void
    {
        Expense::query()->findOrFail($expenseId)->delete();

        session()->flash('status', 'مصرف حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.finance.expense-index', $this->viewData())->layout('layouts.app', [
            'title' => 'مصارف روزانه',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مصارف روزانه'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'expenses' => Expense::query()
                ->with('recordedBy')
                ->when($this->category !== '', fn (Builder $query) => $query->where('category', $this->category))
                ->latest('date')
                ->paginate(12),
            'categoryOptions' => OptionLists::expenseCategories(),
        ];
    }
}
