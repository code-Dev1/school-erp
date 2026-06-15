<?php

namespace App\Livewire\Pages\Access;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    public function updating(string $property): void
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    public function delete(int $roleId): void
    {
        Role::query()->findOrFail($roleId)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        session()->flash('status', 'نقش حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.access.role-index', $this->viewData())->layout('layouts.app', [
            'title' => 'نقش ها',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'نقش ها'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'roles' => Role::query()
                ->withCount(['permissions', 'users'])
                ->when($this->search !== '', fn (Builder $query) => $query->where('name', 'like', '%'.trim($this->search).'%'))
                ->orderBy('name')
                ->paginate(12),
        ];
    }
}
