<?php

namespace App\Livewire\Pages\Guardian;

use App\Models\Guardian;
use App\Support\School\OptionLists;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GuardianIndex extends Component
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

    public function delete(int $guardianId): void
    {
        Guardian::query()->findOrFail($guardianId)->delete();

        session()->flash('status', 'سرپرست حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.guardian.guardian-index', $this->viewData())->layout('layouts.app', [
            'title' => 'سرپرستان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'سرپرستان'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'guardians' => $this->guardians(),
            'statusOptions' => OptionLists::guardianStatuses(),
        ];
    }

    private function guardians()
    {
        return Guardian::query()
            ->withCount('students')
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('whatsapp_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('tazkira_number', 'like', "%{$search}%");
                });
            })
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(12);
    }
}
