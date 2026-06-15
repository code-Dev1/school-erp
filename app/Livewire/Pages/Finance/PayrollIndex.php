<?php

namespace App\Livewire\Pages\Finance;

use App\Models\PayrollRecord;
use App\Support\School\OptionLists;
use Livewire\Component;
use Livewire\WithPagination;

class PayrollIndex extends Component
{
    use WithPagination;

    public function delete(int $payrollId): void
    {
        PayrollRecord::query()->findOrFail($payrollId)->delete();

        session()->flash('status', 'معاش حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.finance.payroll-index', $this->viewData())->layout('layouts.app', [
            'title' => 'معاشات',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'معاشات'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'payrolls' => PayrollRecord::query()
                ->with(['employee', 'recordedBy'])
                ->latest('year')
                ->latest('month')
                ->paginate(12),
            'monthOptions' => OptionLists::months(),
        ];
    }
}
