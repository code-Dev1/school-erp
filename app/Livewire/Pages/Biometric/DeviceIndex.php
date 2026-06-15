<?php

namespace App\Livewire\Pages\Biometric;

use App\Models\BiometricDevice;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DeviceIndex extends Component
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

    public function delete(int $deviceId): void
    {
        BiometricDevice::query()->findOrFail($deviceId)->delete();

        session()->flash('status', 'دستگاه حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.biometric.device-index', $this->viewData())->layout('layouts.app', [
            'title' => 'دستگاه های بیومتریک',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'دستگاه های بیومتریک'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'devices' => BiometricDevice::query()
                ->withCount('logs')
                ->when($this->search !== '', function (Builder $query): void {
                    $search = trim($this->search);
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                })
                ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
                ->latest('id')
                ->paginate(12),
            'statusOptions' => OptionLists::biometricDeviceStatuses(),
            'typeOptions' => OptionLists::biometricDeviceTypes(),
        ];
    }
}
