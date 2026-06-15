<?php

namespace App\Livewire\Pages\Guardian;

use App\Models\Guardian;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GuardianCreate extends Component
{
    public array $form = [
        'name' => '',
        'father_name' => '',
        'phone' => '',
        'whatsapp_number' => '',
        'email' => '',
        'job' => '',
        'tazkira_number' => '',
        'address' => '',
        'status' => 'active',
        'note' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        $data = collect($validated)
            ->map(fn ($value) => $value === '' ? null : $value)
            ->all();

        $parts = preg_split('/\s+/', trim((string) $data['name']), 2);
        $data['first_name'] = $parts[0] ?? $data['name'];
        $data['last_name'] = $parts[1] ?? '';
        $data['contact_number'] = $data['phone'];
        $data['occupation'] = $data['job'] ?? null;
        $data['province'] = null;
        $data['district'] = null;
        $data['village'] = null;

        Guardian::create($data);

        session()->flash('status', 'سرپرست با موفقیت ثبت شد.');

        return redirect()->route('guardian-index');
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.father_name' => ['nullable', 'string', 'max:255'],
            'form.phone' => ['required', 'string', 'max:50'],
            'form.whatsapp_number' => ['nullable', 'string', 'max:50'],
            'form.email' => ['nullable', 'email', 'max:255'],
            'form.job' => ['nullable', 'string', 'max:255'],
            'form.tazkira_number' => ['nullable', 'string', 'max:255'],
            'form.address' => ['nullable', 'string'],
            'form.status' => ['required', Rule::in(['active', 'inactive'])],
            'form.note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.guardian.guardian-create', [
            'statusOptions' => [
                'active' => 'فعال',
                'inactive' => 'غیرفعال',
            ],
        ])->layout('layouts.app', [
            'title' => 'ثبت سرپرست',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'سرپرستان', 'url' => route('guardian-index')],
                ['label' => 'ثبت سرپرست'],
            ],
        ]);
    }
}
