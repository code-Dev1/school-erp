<?php

namespace App\Livewire\Pages\Finance;

use App\Models\FeePayment;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class FeeIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    public function updating(string $property): void
    {
        if ($property === 'status') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('status');
        $this->resetPage();
    }

    public function delete(int $paymentId): void
    {
        FeePayment::query()->findOrFail($paymentId)->delete();

        session()->flash('status', 'پرداخت فیس حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.finance.fee-index', $this->viewData())->layout('layouts.app', [
            'title' => 'فیس شاگردان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'فیس شاگردان'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'payments' => FeePayment::query()
                ->with(['student', 'feeStructure.academicClass', 'feeStructure.feeType', 'feeStructure.academicYear'])
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('payment_date')
                ->paginate(12),
            'statusOptions' => OptionLists::feePaymentStatuses(),
        ];
    }
}
