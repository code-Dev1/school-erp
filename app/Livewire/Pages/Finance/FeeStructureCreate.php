<?php

namespace App\Livewire\Pages\Finance;

use App\Models\FeeStructure;
use App\Support\School\OptionLists;
use Livewire\Component;

class FeeStructureCreate extends Component
{
    public array $form = [
        'class_id' => '',
        'fee_type_id' => '',
        'academic_year_id' => '',
        'amount' => '',
        'due_day' => '1',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        FeeStructure::updateOrCreate(
            [
                'class_id' => $validated['class_id'],
                'fee_type_id' => $validated['fee_type_id'],
                'academic_year_id' => $validated['academic_year_id'],
            ],
            [
                'amount' => $validated['amount'],
                'due_day' => $validated['due_day'],
            ]
        );

        session()->flash('status', 'ساختار فیس ثبت شد.');

        return redirect()->route('fees.index');
    }

    protected function rules(): array
    {
        return [
            'form.class_id' => ['required', 'exists:classes,id'],
            'form.fee_type_id' => ['required', 'exists:fee_types,id'],
            'form.academic_year_id' => ['required', 'exists:academic_years,id'],
            'form.amount' => ['required', 'numeric', 'min:0'],
            'form.due_day' => ['required', 'integer', 'min:1', 'max:31'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.finance.fee-structure-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت ساختار فیس',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'فیس شاگردان', 'url' => route('fees.index')],
                ['label' => 'ثبت ساختار فیس'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classOptions' => OptionLists::academicClasses(),
            'feeTypeOptions' => OptionLists::feeTypes(),
            'academicYearOptions' => OptionLists::academicYears(),
        ];
    }
}
