<?php

namespace App\Livewire\Pages\Sales;

use App\Models\SaleItem;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ItemIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $category = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'category');
        $this->resetPage();
    }

    public function delete(int $itemId): void
    {
        SaleItem::query()->findOrFail($itemId)->delete();

        session()->flash('status', 'Item deleted.');
    }

    public function render()
    {
        return view('livewire.pages.sales.item-index', $this->viewData())->layout('layouts.app', [
            'title' => 'Sale items',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Sale items'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'items' => SaleItem::query()
                ->when($this->search !== '', function (Builder $query): void {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('sku', 'like', "%{$this->search}%");
                })
                ->when($this->category !== '', fn (Builder $query) => $query->where('category', $this->category))
                ->latest('id')
                ->paginate(12),
            'categoryOptions' => OptionLists::saleCategories(),
        ];
    }
}
