<?php

namespace App\Livewire\Pages\Marks;

use App\Models\StudentResult;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MarkIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $classId = '';

    #[Url(except: '')]
    public string $term = '';

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'classId', 'term'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'classId', 'term']);
        $this->resetPage();
    }

    public function delete(int $resultId): void
    {
        StudentResult::query()->findOrFail($resultId)->delete();

        session()->flash('status', 'نمره حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.marks.mark-index', $this->viewData())->layout('layouts.app', [
            'title' => 'نمرات و نتایج',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'نمرات و نتایج'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'results' => $this->results(),
            'classOptions' => OptionLists::academicClasses(),
            'termOptions' => OptionLists::terms(),
        ];
    }

    private function results()
    {
        return StudentResult::query()
            ->with(['student', 'academicClass', 'section', 'subject', 'teacher', 'academicYear'])
            ->when($this->classId !== '', fn (Builder $query) => $query->where('class_id', $this->classId))
            ->when($this->term !== '', fn (Builder $query) => $query->where('term', $this->term))
            ->when($this->search !== '', fn (Builder $query) => $this->searchResults($query))
            ->latest('result_date')
            ->latest('id')
            ->paginate(12);
    }

    private function searchResults(Builder $query): void
    {
        $search = trim($this->search);

        $query->where(function (Builder $query) use ($search): void {
            $query
                ->where('exam_name', 'like', "%{$search}%")
                ->orWhere('grade', 'like', "%{$search}%")
                ->orWhereHas('student', fn (Builder $query) => $query->where('name', 'like', "%{$search}%")->orWhere('asas_number', 'like', "%{$search}%"))
                ->orWhereHas('subject', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"))
                ->orWhereHas('teacher', fn (Builder $query) => $query->where('name', 'like', "%{$search}%"));
        });
    }
}
