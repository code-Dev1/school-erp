<?php

namespace App\Livewire\Pages\Transport;

use App\Models\TransportService;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TransportForm extends Component
{
    public ?TransportService $transportService = null;

    public array $form = [
        'vehicle_plate_number' => '',
        'vehicle_capacity' => '',
        'vehicle_type' => '',
        'driver_name' => '',
        'driver_phone' => '',
        'driver_license_number' => '',
        'driver_monthly_salary' => '0',
        'route_name' => '',
        'pickup_area' => '',
        'dropoff_area' => '',
        'monthly_fee' => '0',
        'status' => 'active',
        'note' => '',
    ];

    public function mount(?TransportService $transportService = null): void
    {
        $this->transportService = $transportService?->exists ? $transportService : null;

        if ($this->transportService) {
            $this->form = [
                'vehicle_plate_number' => $this->transportService->vehicle_plate_number,
                'vehicle_capacity' => (string) $this->transportService->vehicle_capacity,
                'vehicle_type' => $this->transportService->vehicle_type,
                'driver_name' => $this->transportService->driver_name,
                'driver_phone' => $this->transportService->driver_phone,
                'driver_license_number' => $this->transportService->driver_license_number,
                'driver_monthly_salary' => (string) $this->transportService->driver_monthly_salary,
                'route_name' => $this->transportService->route_name,
                'pickup_area' => $this->transportService->pickup_area,
                'dropoff_area' => $this->transportService->dropoff_area,
                'monthly_fee' => (string) $this->transportService->monthly_fee,
                'status' => $this->transportService->status,
                'note' => $this->transportService->note,
            ];
        }
    }

    public function save()
    {
        $validated = $this->validate()['form'];
        $validated['note'] = $validated['note'] ?: null;

        TransportService::updateOrCreate(['id' => $this->transportService?->id], $validated);

        session()->flash('status', 'معلومات ترانسپورت ذخیره شد.');

        return redirect()->route('transport.index');
    }

    protected function rules(): array
    {
        return [
            'form.vehicle_plate_number' => ['required', 'string', 'max:255', Rule::unique('transport_services', 'vehicle_plate_number')->ignore($this->transportService?->id)],
            'form.vehicle_capacity' => ['required', 'integer', 'min:1'],
            'form.vehicle_type' => ['nullable', 'string', 'max:255'],
            'form.driver_name' => ['required', 'string', 'max:255'],
            'form.driver_phone' => ['nullable', 'string', 'max:50'],
            'form.driver_license_number' => ['nullable', 'string', 'max:255', Rule::unique('transport_services', 'driver_license_number')->ignore($this->transportService?->id)],
            'form.driver_monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'form.route_name' => ['required', 'string', 'max:255'],
            'form.pickup_area' => ['nullable', 'string', 'max:255'],
            'form.dropoff_area' => ['nullable', 'string', 'max:255'],
            'form.monthly_fee' => ['nullable', 'numeric', 'min:0'],
            'form.status' => ['required', Rule::in(array_keys(OptionLists::activeStatuses()))],
            'form.note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render()
    {
        $title = $this->transportService ? 'ویرایش ترانسپورت' : 'ثبت ترانسپورت';

        return view('livewire.pages.transport.transport-form', $this->viewData())->layout('layouts.app', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'ترانسپورت', 'url' => route('transport.index')],
                ['label' => $title],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'title' => $this->transportService ? 'ویرایش ترانسپورت' : 'ثبت ترانسپورت',
            'statusOptions' => OptionLists::activeStatuses(),
        ];
    }
}
