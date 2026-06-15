<?php

namespace App\Livewire\Pages\Transport;

use App\Models\StudentTransport;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AssignmentForm extends Component
{
    public ?StudentTransport $assignment = null;

    public array $form = [
        'student_id' => '',
        'transport_service_id' => '',
        'academic_year_id' => '',
        'fee_amount' => '0',
        'starts_at' => '',
        'ends_at' => '',
        'status' => 'active',
        'note' => '',
    ];

    public function mount(?StudentTransport $assignment = null): void
    {
        $this->assignment = $assignment?->exists ? $assignment : null;

        if ($this->assignment) {
            $this->form = [
                'student_id' => (string) $this->assignment->student_id,
                'transport_service_id' => (string) $this->assignment->transport_service_id,
                'academic_year_id' => (string) $this->assignment->academic_year_id,
                'fee_amount' => (string) $this->assignment->fee_amount,
                'starts_at' => $this->assignment->starts_at?->format('Y-m-d'),
                'ends_at' => $this->assignment->ends_at?->format('Y-m-d'),
                'status' => $this->assignment->status,
                'note' => $this->assignment->note,
            ];
        }
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $validated['academic_year_id'] = $validated['academic_year_id'] ?: null;
        $validated['starts_at'] = $validated['starts_at'] ?: null;
        $validated['ends_at'] = $validated['ends_at'] ?: null;
        $validated['note'] = $validated['note'] ?: null;

        StudentTransport::updateOrCreate(['id' => $this->assignment?->id], $validated);

        session()->flash('status', 'ترانسپورت شاگرد ذخیره شد.');

        return redirect()->route('transport.assignments.index');
    }

    protected function rules(): array
    {
        return [
            'form.student_id' => ['required', 'exists:students,id'],
            'form.transport_service_id' => [
                'required',
                'exists:transport_services,id',
                Rule::unique('student_transport', 'transport_service_id')
                    ->where('student_id', $this->form['student_id'])
                    ->ignore($this->assignment?->id),
            ],
            'form.academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'form.fee_amount' => ['nullable', 'numeric', 'min:0'],
            'form.starts_at' => ['nullable', 'date'],
            'form.ends_at' => ['nullable', 'date', 'after_or_equal:form.starts_at'],
            'form.status' => ['required', Rule::in(array_keys(OptionLists::activeStatuses()))],
            'form.note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        $title = $this->assignment ? 'ویرایش ترانسپورت شاگرد' : 'ثبت ترانسپورت شاگرد';

        return view('livewire.pages.transport.assignment-form', $this->viewData())->layout('layouts.app', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت شاگردان', 'url' => route('transport.assignments.index')],
                ['label' => $title],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'title' => $this->assignment ? 'ویرایش ترانسپورت شاگرد' : 'ثبت ترانسپورت شاگرد',
            'studentOptions' => OptionLists::students(),
            'transportOptions' => OptionLists::transportServices(),
            'academicYearOptions' => OptionLists::academicYears(),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
