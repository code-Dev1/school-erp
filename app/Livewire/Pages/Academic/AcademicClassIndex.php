<?php

namespace App\Livewire\Pages\Academic;

use App\Models\AcademicClass;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicClassIndex extends Component
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

    public function delete(int $classId): void
    {
        AcademicClass::query()->findOrFail($classId)->delete();

        session()->flash('status', 'صنف حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.academic.class-index', $this->viewData())->layout('layouts.app', [
            'title' => 'صنف ها',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'صنف ها'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classes' => $this->classes(),
        ];
    }

    private function classes()
    {
        return AcademicClass::query()
            ->withCount(['sections', 'students', 'subjects', 'timetables'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('academic_year', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('grade_level')
            ->orderBy('name')
            ->paginate(12);
    }
}
