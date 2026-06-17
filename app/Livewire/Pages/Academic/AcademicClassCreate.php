<?php

namespace App\Livewire\Pages\Academic;

use App\Models\AcademicClass;
use App\Models\Section;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AcademicClassCreate extends Component
{
    public array $form = [
        'name' => '',
        'grade_level' => '',
        'section_id' => '',
        'is_active' => true,
    ];

    public function save()
    {
        $validated = $this->validate()['form'];
        // dd($validated);
        AcademicClass::create([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
            'section_id' => $validated['section_id'],
            'is_active' => (bool) $validated['is_active'],
        ]);

        session()->flash('status', 'صنف با موفقیت ثبت شد.');

        return redirect()->route('classes.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('classes', 'name')],
            'form.grade_level' => ['nullable', 'integer', 'min:1', 'max:12'],
            'form.section_id' => ['nullable', 'exists:sections,id'],
            'form.is_active' => ['boolean'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.academic.class-create', $this->viewData())
            ->layout('layouts.app', [
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
            'sections' => Section::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ];
    }
}
