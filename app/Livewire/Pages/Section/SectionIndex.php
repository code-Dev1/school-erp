<?php

namespace App\Livewire\Pages\Section;

use App\Models\Section;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SectionIndex extends Component
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

    public function delete(int $sectionId): void
    {
        Section::query()->findOrFail($sectionId)->delete();

        session()->flash('status', 'بخش حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.section.section-index', $this->viewData())
            ->layout('layouts.app', [
                'title' => 'بخش ها',
                'breadcrumbs' => [
                    ['label' => 'داشبورد', 'url' => route('dashboard')],
                    ['label' => 'بخش ها'],
                ],
            ]);
    }

    private function viewData(): array
    {
        return [
            'sections' => $this->sections(),
        ];
    }

    private function sections()
    {
        return Section::query()
            ->withCount(['academicClass', 'students', 'timetables', 'results'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(12);
    }
}
