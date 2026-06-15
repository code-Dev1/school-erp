<?php

namespace App\Livewire\Pages\Transport;

use App\Models\TransportService;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TransportIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $status = '';

    public function updating(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'status');
        $this->resetPage();
    }

    public function delete(int $serviceId): void
    {
        TransportService::query()->findOrFail($serviceId)->delete();

        session()->flash('status', 'معلومات ترانسپورت حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.transport.transport-index', $this->viewData())->layout('layouts.app', [
            'title' => 'ترانسپورت',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'services' => TransportService::query()
                ->withCount('assignments')
                ->when($this->search !== '', function (Builder $query): void {
                    $search = trim($this->search);

                    $query->where(function (Builder $query) use ($search): void {
                        $query->where('vehicle_plate_number', 'like', "%{$search}%")
                            ->orWhere('driver_name', 'like', "%{$search}%")
                            ->orWhere('driver_phone', 'like', "%{$search}%")
                            ->orWhere('route_name', 'like', "%{$search}%")
                            ->orWhere('pickup_area', 'like', "%{$search}%")
                            ->orWhere('dropoff_area', 'like', "%{$search}%");
                    });
                })
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('id')
                ->paginate(12),
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
