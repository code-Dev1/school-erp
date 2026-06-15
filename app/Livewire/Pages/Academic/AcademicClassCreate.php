<?php

namespace App\Livewire\Pages\Academic;

use App\Models\AcademicClass;
use App\Support\School\OptionLists;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AcademicClassCreate extends Component
{
    public array $form = [
        'name' => '',
        'grade_level' => '',
        'academic_year' => '',
        'status' => 'active',
        'description' => '',
        'note' => '',
        'section_names' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        $class = AcademicClass::create([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'] ?: null,
            'academic_year' => $validated['academic_year'] ?: null,
            'status' => $validated['status'],
            'description' => $validated['description'] ?: null,
            'note' => $validated['note'] ?: null,
            'is_active' => true,
        ]);

        foreach ($this->sectionNames($validated['section_names'] ?? '') as $name) {
            $class->sections()->firstOrCreate(['name' => $name], ['is_active' => true]);
        }

        session()->flash('status', 'صنف با موفقیت ثبت شد.');

        return redirect()->route('classes.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('classes', 'name')],
            'form.grade_level' => ['nullable', 'integer', 'min:1', 'max:12'],
            'form.academic_year' => ['nullable', 'string', 'max:255'],
            'form.status' => ['required', Rule::in(['active', 'inactive'])],
            'form.description' => ['nullable', 'string'],
            'form.note' => ['nullable', 'string'],
            'form.section_names' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.academic.class-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت صنف',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'صنف ها', 'url' => route('classes.index')],
                ['label' => 'ثبت صنف'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }

    private function sectionNames(string $value): array
    {
        return Arr::where(
            array_unique(array_map('trim', preg_split('/[\r\n,]+/', $value) ?: [])),
            fn (?string $name) => filled($name)
        );
    }
}
