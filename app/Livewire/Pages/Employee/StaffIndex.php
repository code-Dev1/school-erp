<?php

namespace App\Livewire\Pages\Employee;

use App\Enums\Employees\EmployeeType;
use App\Models\Employee;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StaffIndex extends Component
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

    public function delete(int $staffId): void
    {
        Employee::query()
            ->where('type', EmployeeType::Staff->value)
            ->findOrFail($staffId)
            ->delete();

        session()->flash('status', 'کارمند حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.employee.staff-index', $this->viewData())->layout('layouts.app', [
            'title' => 'کارمندان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'کارمندان'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'staffMembers' => $this->staffMembers(),
            'statusOptions' => OptionLists::employeeStatuses(),
        ];
    }

    private function staffMembers()
    {
        return Employee::query()
            ->where('type', EmployeeType::Staff->value)
            ->with('manager')
            ->when($this->search !== '', fn (Builder $query) => $this->searchStaff($query))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(12);
    }

    private function searchStaff(Builder $query): void
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
                ->orWhere('job_title', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%");
        });
    }
}
