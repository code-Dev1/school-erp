<?php

namespace App\Livewire\Pages\Settings;

use App\Models\SystemSetting;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SettingCreate extends Component
{
    public array $form = [
        'key' => '',
        'value' => '',
        'group' => 'general',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        SystemSetting::create([
            'key' => $validated['key'],
            'value' => $validated['value'] ?: null,
            'group' => $validated['group'] ?: 'general',
            'updated_by' => auth()->id(),
        ]);

        session()->flash('status', 'تنظیم ثبت شد.');

        return redirect()->route('settings.index');
    }

    protected function rules(): array
    {
        return [
            'form.key' => ['required', 'string', 'max:255', Rule::unique('system_settings', 'key')],
            'form.value' => ['nullable', 'string'],
            'form.group' => ['required', 'string', 'max:255'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.settings.setting-create')->layout('layouts.app', [
            'title' => 'ثبت تنظیم',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'تنظیمات عمومی', 'url' => route('settings.index')],
                ['label' => 'ثبت تنظیم'],
            ],
        ]);
    }
}
