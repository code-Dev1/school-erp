<?php

namespace App\Livewire\Pages\Student;

use App\Enums\Students\StudentStatus;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudentIndex extends Component
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

    public function delete(int $studentId): void
    {
        Student::query()->findOrFail($studentId)->delete();

        session()->flash('status', 'شاگرد حذف شد.');
    }

    public function render()
    {
        $students = Student::query()
            ->with(['academicClass', 'section', 'academicYear'])
            ->when($this->search !== '', function (Builder $query): void {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('student_id', 'like', "%{$search}%")
                        ->orWhere('asas_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('father_name', 'like', "%{$search}%")
                        ->orWhere('tazkira_number', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('id')
            ->paginate(12);

        return view('livewire.pages.student.student-index', [
            'students' => $students,
            'statusOptions' => [
                StudentStatus::Active->value => 'فعال',
                StudentStatus::Transferred->value => 'تبدیل شده',
                StudentStatus::Graduated->value => 'فارغ',
                StudentStatus::Expelled->value => 'اخراج شده',
            ],
        ])->layout('layouts.app', [
            'title' => 'شاگردان',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'شاگردان'],
            ],
        ]);
    }
}
