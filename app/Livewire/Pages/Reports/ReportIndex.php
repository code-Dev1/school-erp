<?php

namespace App\Livewire\Pages\Reports;

use App\Models\ReportExport;
use App\Support\School\OptionLists;
use Livewire\Component;
use Livewire\WithPagination;

class ReportIndex extends Component
{
    use WithPagination;

    public function delete(int $reportId): void
    {
        ReportExport::query()->findOrFail($reportId)->delete();

        session()->flash('status', 'گزارش حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.reports.report-index', $this->viewData())->layout('layouts.app', [
            'title' => 'گزارش ها',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'گزارش ها'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'reports' => ReportExport::query()
                ->with('generatedBy')
                ->latest('generated_at')
                ->paginate(12),
            'typeOptions' => OptionLists::reportTypes(),
        ];
    }
}
