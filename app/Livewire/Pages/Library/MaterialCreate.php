<?php

namespace App\Livewire\Pages\Library;

use App\Models\TeachingMaterial;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MaterialCreate extends Component
{
    public array $form = [
        'class_id' => '',
        'subject_id' => '',
        'teacher_id' => '',
        'title' => '',
        'type' => 'note',
        'file_path' => '',
        'external_url' => '',
        'description' => '',
        'status' => 'active',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        TeachingMaterial::create([
            'class_id' => $validated['class_id'] ?: null,
            'subject_id' => $validated['subject_id'] ?: null,
            'teacher_id' => $validated['teacher_id'] ?: null,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $validated['file_path'] ?: null,
            'external_url' => $validated['external_url'] ?: null,
            'description' => $validated['description'] ?: null,
            'status' => $validated['status'],
        ]);

        session()->flash('status', 'مواد درسی ثبت شد.');

        return redirect()->route('library.materials.index');
    }

    protected function rules(): array
    {
        return [
            'form.class_id' => ['nullable', 'exists:classes,id'],
            'form.subject_id' => ['nullable', Rule::exists('subjects', 'id')->where(fn ($query) => $query->where('class_id', $this->form['class_id'] ?? null))],
            'form.teacher_id' => ['nullable', 'exists:employees,id'],
            'form.title' => ['required', 'string', 'max:255'],
            'form.type' => ['required', Rule::in(array_keys(OptionLists::materialTypes()))],
            'form.file_path' => ['nullable', 'string', 'max:255'],
            'form.external_url' => ['nullable', 'url', 'max:255'],
            'form.description' => ['nullable', 'string'],
            'form.status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function render()
    {
        return view('livewire.pages.library.material-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت مواد درسی',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مواد درسی', 'url' => route('library.materials.index')],
                ['label' => 'ثبت مواد درسی'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classOptions' => OptionLists::academicClasses(),
            'subjectOptions' => OptionLists::subjects($this->form['class_id'] ?? null),
            'teacherOptions' => OptionLists::teachers(),
            'typeOptions' => OptionLists::materialTypes(),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
