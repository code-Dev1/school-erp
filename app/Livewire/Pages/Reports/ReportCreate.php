<?php

namespace App\Livewire\Pages\Reports;

use App\Enums\Reports\ReportType;
use App\Models\ReportExport;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReportCreate extends Component
{
    public array $form = [
        'type' => '',
        'title' => '',
        'filter_note' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        ReportExport::create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'filters' => $validated['filter_note'] ? ['note' => $validated['filter_note']] : null,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
        ]);

        session()->flash('status', 'گزارش ثبت شد.');

        return redirect()->route('reports.index');
    }

    protected function rules(): array
    {
        return [
            'form.type' => ['required', Rule::in(array_column(ReportType::cases(), 'value'))],
            'form.title' => ['required', 'string', 'max:255'],
            'form.filter_note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.reports.report-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت گزارش',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'گزارش ها', 'url' => route('reports.index')],
                ['label' => 'ثبت گزارش'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'typeOptions' => OptionLists::reportTypes(),
        ];
    }
}
