<?php

namespace App\Livewire\Pages\Academic;

use App\Models\AcademicYear;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicYearIndex extends Component
{
    use WithPagination;

    public function delete(int $yearId): void
    {
        AcademicYear::query()->findOrFail($yearId)->delete();

        session()->flash('status', 'سال تعلیمی حذف شد.');
    }

    public function render()
    {
        return view('livewire.pages.academic.academic-year-index', [
            'years' => AcademicYear::query()->latest('id')->paginate(12),
        ])->layout('layouts.app', [
            'title' => 'سال های تعلیمی',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'سال های تعلیمی'],
            ],
        ]);
    }
}
