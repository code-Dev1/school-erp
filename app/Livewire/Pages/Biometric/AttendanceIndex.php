<?php

namespace App\Livewire\Pages\Biometric;

use App\Models\AttendanceSummary;
use App\Models\Employee;
use App\Models\Student;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $date = '';

    #[Url(except: '')]
    public string $status = '';

    public function mount(): void
    {
        $this->date = $this->date ?: now()->format('Y-m-d');
    }

    public function updating(string $property): void
    {
        if (in_array($property, ['date', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->status = '';
        $this->date = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function delete(int $attendanceId): void
    {
        AttendanceSummary::query()->findOrFail($attendanceId)->delete();

        session()->flash('status', 'حاضری حذف شد.');
    }

    public function render()
    {
        $title = $this->isStudentPage() ? 'حاضری شاگردان' : 'حاضری کارمندان';

        return view('livewire.pages.biometric.attendance-index', $this->viewData())->layout('layouts.app', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => $title],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'attendanceRows' => $this->attendanceRows(),
            'statusOptions' => OptionLists::attendanceStatuses(),
            'createRoute' => $this->isStudentPage() ? route('attendance.students.create') : route('attendance.staff.create'),
            'title' => $this->isStudentPage() ? 'حاضری شاگردان' : 'حاضری کارمندان',
        ];
    }

    private function attendanceRows()
    {
        return AttendanceSummary::query()
            ->with('person')
            ->where('person_type', $this->personClass())
            ->when($this->date !== '', fn (Builder $query) => $query->whereDate('date', $this->date))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('date')
            ->latest('id')
            ->paginate(12);
    }

    private function personClass(): string
    {
        return $this->isStudentPage() ? Student::class : Employee::class;
    }

    private function isStudentPage(): bool
    {
        return request()->routeIs('attendance.students');
    }
}
