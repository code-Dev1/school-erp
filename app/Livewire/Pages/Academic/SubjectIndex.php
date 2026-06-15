<?php

namespace App\Livewire\Pages\Academic;

use App\Models\Subject;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function delete(int $subjectId): void
    {
        Subject::query()->findOrFail($subjectId)->delete();

        session()->flash('status', 'مضمون حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.academic.subject-index', $this->viewData())->layout('layouts.app', [
            'title' => 'مضامین',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'مضامین'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'subjects' => $this->subjects(),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }

    private function subjects()
    {
        return Subject::query()
            ->with('academicClass')
            ->withCount(['timetables', 'results'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->orderBy('name')
            ->paginate(12);
    }
}
