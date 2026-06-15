<?php

namespace App\Livewire\Pages\Library;

use App\Models\LibraryLoan;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LoanIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    public function clearFilters(): void
    {
        $this->reset('status');
        $this->resetPage();
    }

    public function delete(int $loanId): void
    {
        LibraryLoan::query()->findOrFail($loanId)->delete();

        session()->flash('status', 'امانت حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.library.loan-index', $this->viewData())->layout('layouts.app', [
            'title' => 'امانت کتاب',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'امانت کتاب'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'loans' => LibraryLoan::query()
                ->with(['book', 'student', 'employee'])
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('borrowed_at')
                ->paginate(12),
            'statusOptions' => OptionLists::loanStatuses(),
        ];
    }
}
