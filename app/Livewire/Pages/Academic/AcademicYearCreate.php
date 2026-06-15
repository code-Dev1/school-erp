<?php

namespace App\Livewire\Pages\Academic;

use App\Models\AcademicYear;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AcademicYearCreate extends Component
{
    public array $form = [
        'name' => '',
        'starts_at' => '',
        'ends_at' => '',
        'is_active' => true,
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        AcademicYear::create($validated);

        session()->flash('status', 'سال تعلیمی ثبت شد.');

        return redirect()->route('academic-years.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('academic_years', 'name')],
            'form.starts_at' => ['required', 'date'],
            'form.ends_at' => ['required', 'date', 'after:form.starts_at'],
            'form.is_active' => ['boolean'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.academic.academic-year-create')->layout('layouts.app', [
            'title' => 'ثبت سال تعلیمی',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'سال های تعلیمی', 'url' => route('academic-years.index')],
                ['label' => 'ثبت سال تعلیمی'],
            ],
        ]);
    }
}
