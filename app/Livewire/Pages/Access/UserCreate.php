<?php

namespace App\Livewire\Pages\Access;

use App\Models\User;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserCreate extends Component
{
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'status' => 'active',
        'roles' => [],
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'status' => $validated['status'],
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        session()->flash('status', 'کاربر ثبت شد.');

        return redirect()->route('users.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'form.password' => ['required', 'string', 'min:8'],
            'form.status' => ['required', Rule::in(['active', 'inactive'])],
            'form.roles' => ['array'],
            'form.roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.access.user-create', [
            'roleOptions' => OptionLists::roles(),
            'statusOptions' => OptionLists::activeStatuses(),
        ])->layout('layouts.app', [
            'title' => 'ثبت کاربر',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کاربران', 'url' => route('users.index')],
                ['label' => 'ثبت کاربر'],
            ],
        ]);
    }
}
