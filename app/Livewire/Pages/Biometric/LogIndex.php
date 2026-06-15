<?php

namespace App\Livewire\Pages\Biometric;

use App\Models\BiometricLog;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LogIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $deviceId = '';

    public function updating(string $property): void
    {
        if ($property === 'deviceId') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('deviceId');
        $this->resetPage();
    }

    public function delete(int $logId): void
    {
        BiometricLog::query()->findOrFail($logId)->delete();

        session()->flash('status', 'لاگ حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.biometric.log-index', $this->viewData())->layout('layouts.app', [
            'title' => 'لاگ های بیومتریک',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'لاگ های بیومتریک'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'logs' => BiometricLog::query()
                ->with('device')
                ->when($this->deviceId !== '', fn (Builder $query) => $query->where('device_id', $this->deviceId))
                ->latest('timestamp')
                ->paginate(12),
            'deviceOptions' => OptionLists::biometricDevices(),
            'typeOptions' => OptionLists::biometricLogTypes(),
        ];
    }
}
