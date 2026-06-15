<?php

namespace App\Livewire\Pages\Transport;

use App\Models\StudentTransport;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AssignmentIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $status = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('status');
        $this->resetPage();
    }

    public function delete(int $assignmentId): void
    {
        StudentTransport::query()->findOrFail($assignmentId)->delete();

        session()->flash('status', 'ترانسپورت شاگرد حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.transport.assignment-index', $this->viewData())->layout('layouts.app', [
            'title' => 'ترانسپورت شاگردان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت شاگردان'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'assignments' => StudentTransport::query()
                ->with(['student', 'transportService', 'academicYear'])
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('id')
                ->paginate(12),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
