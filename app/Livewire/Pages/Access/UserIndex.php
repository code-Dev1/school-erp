<?php

namespace App\Livewire\Pages\Access;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public function delete(int $userId): void
    {
        User::query()->findOrFail($userId)->delete();

        session()->flash('status', 'کاربر حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.access.user-index', [
            'users' => User::query()->with('roles')->latest('id')->paginate(12),
        ])->layout('layouts.app', [
            'title' => 'کاربران',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کاربران'],
            ],
        ]);
    }
}
