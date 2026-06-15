<?php

namespace App\Livewire\Pages\Biometric;

use App\Enums\Biometric\BiometricDeviceStatus;
use App\Enums\Biometric\BiometricDeviceType;
use App\Models\BiometricDevice;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DeviceCreate extends Component
{
    public array $form = [
        'name' => '',
        'ip_address' => '',
        'port' => '4370',
        'location' => '',
        'device_type' => 'zkteco',
        'status' => 'active',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        BiometricDevice::create([
            'name' => $validated['name'],
            'ip_address' => $validated['ip_address'],
            'port' => $validated['port'],
            'location' => $validated['location'] ?: null,
            'device_type' => $validated['device_type'],
            'status' => $validated['status'],
        ]);

        session()->flash('status', 'دستگاه بیومتریک ثبت شد.');

        return redirect()->route('biometric.devices.index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.ip_address' => ['required', 'ip', Rule::unique('biometric_devices', 'ip_address')],
            'form.port' => ['required', 'integer', 'min:1', 'max:65535'],
            'form.location' => ['nullable', 'string', 'max:255'],
            'form.device_type' => ['required', Rule::in(array_column(BiometricDeviceType::cases(), 'value'))],
            'form.status' => ['required', Rule::in(array_column(BiometricDeviceStatus::cases(), 'value'))],
        ];
    }

    public function render()
    {
        return view('livewire.pages.biometric.device-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت دستگاه بیومتریک',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'دستگاه ها', 'url' => route('biometric.devices.index')],
                ['label' => 'ثبت دستگاه'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'typeOptions' => OptionLists::biometricDeviceTypes(),
            'statusOptions' => OptionLists::biometricDeviceStatuses(),
        ];
    }
}
