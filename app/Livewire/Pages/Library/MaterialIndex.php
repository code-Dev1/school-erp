<?php

namespace App\Livewire\Pages\Library;

use App\Models\TeachingMaterial;
use App\Support\School\OptionLists;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialIndex extends Component
{
    use WithPagination;

    public function delete(int $materialId): void
    {
        TeachingMaterial::query()->findOrFail($materialId)->delete();

        session()->flash('status', 'مواد درسی حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.library.material-index', $this->viewData())->layout('layouts.app', [
            'title' => 'مواد درسی',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مواد درسی'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'materials' => TeachingMaterial::query()
                ->with(['academicClass', 'subject', 'teacher'])
                ->latest('id')
                ->paginate(12),
            'typeOptions' => OptionLists::materialTypes(),
        ];
    }
}
