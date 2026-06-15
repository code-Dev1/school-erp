<?php

namespace App\Livewire\Pages\Timetable;

use App\Models\Timetable;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TimetableIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $classId = '';

    #[Url(except: '')]
    public string $day = '';

    public function updating(string $property): void
    {
        if (in_array($property, ['classId', 'day'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['classId', 'day']);
        $this->resetPage();
    }

    public function delete(int $timetableId): void
    {
        Timetable::query()->findOrFail($timetableId)->delete();

        session()->flash('status', 'تقسیم اوقات حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.timetable.timetable-index', $this->viewData())->layout('layouts.app', [
            'title' => 'تقسیم اوقات',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'تقسیم اوقات'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'timetables' => $this->timetables(),
            'classOptions' => OptionLists::academicClasses(),
            'dayOptions' => OptionLists::daysOfWeek(),
        ];
    }

    private function timetables()
    {
        return Timetable::query()
            ->with(['academicClass', 'section', 'subject', 'teacher', 'academicYear'])
            ->when($this->classId !== '', fn (Builder $query) => $query->where('class_id', $this->classId))
            ->when($this->day !== '', fn (Builder $query) => $query->where('day_of_week', $this->day))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(12);
    }
}
