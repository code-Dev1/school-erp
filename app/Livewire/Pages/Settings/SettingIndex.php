<?php

namespace App\Livewire\Pages\Settings;

use App\Models\SystemSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SettingIndex extends Component
{
    use WithPagination;

    public function delete(int $settingId): void
    {
        SystemSetting::query()->findOrFail($settingId)->delete();

        session()->flash('status', 'تنظیم حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.settings.setting-index', [
            'settings' => SystemSetting::query()->with('updatedBy')->orderBy('group')->orderBy('key')->paginate(12),
        ])->layout('layouts.app', [
            'title' => 'تنظیمات عمومی',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'تنظیمات عمومی'],
            ],
        ]);
    }
}
