<?php

namespace App\Livewire\Pages\Sales;

use App\Models\StudentSale;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SaleIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('status');
        $this->resetPage();
    }

    public function delete(int $saleId): void
    {
        DB::transaction(function () use ($saleId): void {
            $sale = StudentSale::query()->with('lines.item')->findOrFail($saleId);

            foreach ($sale->lines as $line) {
                $line->item?->increment('stock_quantity', $line->quantity);
            }

            $sale->delete();
        });

        session()->flash('status', 'Sale deleted and stock restored.');
    }

    public function render()
    {
        return view('livewire.pages.sales.sale-index', $this->viewData())->layout('layouts.app', [
            'title' => 'Student sales',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Student sales'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'sales' => StudentSale::query()
                ->with('student')
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('sold_at')
                ->latest('id')
                ->paginate(12),
            'statusOptions' => OptionLists::saleStatuses(),
        ];
    }
}
