<?php

namespace App\Livewire\Pages\Employee;

use App\Enums\Employees\EmployeeType;
use App\Models\Employee;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherIndex extends Component
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

    public function delete(int $teacherId): void
    {
        Employee::query()
            ->where('type', EmployeeType::Teacher->value)
            ->findOrFail($teacherId)
            ->delete();

        session()->flash('status', 'استاد حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.employee.teacher-index', $this->viewData())->layout('layouts.app', [
            'title' => 'استادان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'استادان'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'teachers' => $this->teachers(),
            'statusOptions' => OptionLists::employeeStatuses(),
        ];
    }

    private function teachers()
    {
        return Employee::query()
            ->where('type', EmployeeType::Teacher->value)
            ->withCount(['timetables', 'results'])
            ->when($this->search !== '', fn (Builder $query) => $this->searchTeachers($query))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(12);
    }

    private function searchTeachers(Builder $query): void
    {
        $search = trim($this->search);

        $query->where(function (Builder $query) use ($search): void {
            $query
                ->where('employee_id', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('father_name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%");
        });
    }
}
