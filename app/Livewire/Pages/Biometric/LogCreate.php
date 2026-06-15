<?php

namespace App\Livewire\Pages\Biometric;

use App\Enums\Biometric\BiometricLogType;
use App\Models\BiometricLog;
use App\Services\Biometric\ProcessBiometricLog;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LogCreate extends Component
{
    public array $form = [
        'biometric_uid' => '',
        'device_id' => '',
        'timestamp' => '',
        'log_type' => 'check_in',
    ];

    public function mount(): void
    {
        $this->form['timestamp'] = now()->format('Y-m-d\TH:i');
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        $log = BiometricLog::create([
            'biometric_uid' => $validated['biometric_uid'],
            'device_id' => $validated['device_id'],
            'timestamp' => $validated['timestamp'],
            'check_time' => $validated['timestamp'],
            'log_type' => $validated['log_type'],
            'check_type' => $validated['log_type'],
        ]);

        app(ProcessBiometricLog::class)->handle($log);

        session()->flash('status', 'لاگ بیومتریک ثبت شد.');

        return redirect()->route('biometric.logs.index');
    }

    protected function rules(): array
    {
        return [
            'form.biometric_uid' => ['required', 'integer', 'min:1'],
            'form.device_id' => ['required', 'exists:biometric_devices,id'],
            'form.timestamp' => ['required', 'date'],
            'form.log_type' => ['required', Rule::in(array_column(BiometricLogType::cases(), 'value'))],
        ];
    }

    public function render()
    {
        return view('livewire.pages.biometric.log-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت لاگ بیومتریک',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'لاگ های بیومتریک', 'url' => route('biometric.logs.index')],
                ['label' => 'ثبت لاگ'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'deviceOptions' => OptionLists::biometricDevices(),
            'typeOptions' => OptionLists::biometricLogTypes(),
        ];
    }
}
