<?php

namespace App\Livewire\Pages\Academic;

use App\Models\Subject;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SubjectCreate extends Component
{
    public array $form = [
        'class_id' => '',
        'name' => '',
        'code' => '',
        'description' => '',
        'status' => 'active',
        'note' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        Subject::create([
            'class_id' => $validated['class_id'] ?: null,
            'name' => $validated['name'],
            'code' => $validated['code'] ?: null,
            'description' => $validated['description'] ?: null,
            'status' => $validated['status'],
            'note' => $validated['note'] ?: null,
            'is_active' => $validated['status'] === 'active',
        ]);

        session()->flash('status', 'مضمون با موفقیت ثبت شد.');

        return redirect()->route('subjects.index');
    }

    protected function rules(): array
    {
        return [
            'form.class_id' => ['nullable', 'exists:classes,id'],
            'form.name' => ['required', 'string', 'max:255', Rule::unique('subjects', 'name')],
            'form.code' => ['nullable', 'string', 'max:255', Rule::unique('subjects', 'code')],
            'form.description' => ['nullable', 'string'],
            'form.status' => ['required', Rule::in(['active', 'inactive'])],
            'form.note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.academic.subject-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت مضمون',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مضامین', 'url' => route('subjects.index')],
                ['label' => 'ثبت مضمون'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classOptions' => OptionLists::academicClasses(),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
