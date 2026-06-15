<?php

namespace App\Livewire\Pages\Access;

use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleCreate extends Component
{
    public array $form = [
        'name' => '',
        'permissions' => [],
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        session()->flash('status', 'نقش با موفقیت ثبت شد.');

        return redirect()->route('roles.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->where('guard_name', 'web')],
            'form.permissions' => ['array'],
            'form.permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.access.role-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت نقش',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'نقش ها', 'url' => route('roles.index')],
                ['label' => 'ثبت نقش'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'permissionOptions' => OptionLists::permissions(),
        ];
    }
}
