<?php

namespace App\Livewire\Pages\Section;

use App\Models\Section;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SectionCreate extends Component
{
    public array $form = [
        'name' => '',
        'is_active' => true,
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        Section::create([
            'name' => $validated['name'],
            'is_active' => (bool) $validated['is_active'],
        ]);

        session()->flash('status', 'بخش با موفقیت ثبت شد.');

        return redirect()->route('sections.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('sections', 'name')],
            'form.is_active' => ['boolean'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.section.section-create')
            ->layout('layouts.app', [
                'title' => 'ثبت بخش',
                'breadcrumbs' => [
                    ['label' => 'داشبورد', 'url' => route('dashboard')],
                    ['label' => 'بخش ها', 'url' => route('sections.index')],
                    ['label' => 'ثبت بخش'],
                ],
            ]);
    }
}
