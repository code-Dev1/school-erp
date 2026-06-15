<?php

namespace App\Livewire\Pages\Access;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionIndex extends Component
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

    public function delete(int $permissionId): void
    {
        Permission::query()->findOrFail($permissionId)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        session()->flash('status', 'صلاحیت حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.access.permission-index', $this->viewData())->layout('layouts.app', [
            'title' => 'صلاحیت ها',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'صلاحیت ها'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'permissions' => Permission::query()
                ->withCount('roles')
                ->when($this->search !== '', fn (Builder $query) => $query->where('name', 'like', '%'.trim($this->search).'%'))
                ->orderBy('name')
                ->paginate(16),
        ];
    }
}
