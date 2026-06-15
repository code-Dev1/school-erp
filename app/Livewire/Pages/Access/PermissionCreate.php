<?php

namespace App\Livewire\Pages\Access;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionCreate extends Component
{
    public array $form = [
        'name' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        session()->flash('status', 'صلاحیت با موفقیت ثبت شد.');

        return redirect()->route('permissions.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->where('guard_name', 'web')],
        ];
    }

    public function render()
    {
        return view('livewire.pages.access.permission-create')->layout('layouts.app', [
            'title' => 'ثبت صلاحیت',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'صلاحیت ها', 'url' => route('permissions.index')],
                ['label' => 'ثبت صلاحیت'],
            ],
        ]);
    }
}
