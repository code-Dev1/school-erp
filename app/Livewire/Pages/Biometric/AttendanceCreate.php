<?php

namespace App\Livewire\Pages\Biometric;

use App\Enums\Biometric\AttendanceStatus;
use App\Models\AttendanceSummary;
use App\Models\Employee;
use App\Models\Student;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AttendanceCreate extends Component
{
    public array $form = [
        'person_id' => '',
        'date' => '',
        'status' => 'present',
        'check_in' => '',
        'check_out' => '',
    ];

    public function mount(): void
    {
        $this->form['date'] = now()->format('Y-m-d');
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        AttendanceSummary::updateOrCreate(
            [
                'person_id' => $validated['person_id'],
                'person_type' => $this->personClass(),
                'date' => $validated['date'],
            ],
            [
                'status' => $validated['status'],
                'check_in' => $validated['check_in'] ?: null,
                'check_out' => $validated['check_out'] ?: null,
            ]
        );

        session()->flash('status', 'حاضری ثبت شد.');

        return redirect()->route($this->isStudentPage() ? 'attendance.students' : 'attendance.staff');
    }

    protected function rules(): array
    {
        return [
            'form.person_id' => ['required', 'integer', Rule::exists($this->isStudentPage() ? 'students' : 'employees', 'id')],
            'form.date' => ['required', 'date'],
            'form.status' => ['required', Rule::in(array_column(AttendanceStatus::cases(), 'value'))],
            'form.check_in' => ['nullable', 'date_format:H:i'],
            'form.check_out' => ['nullable', 'date_format:H:i', 'after_or_equal:form.check_in'],
        ];
    }

    public function render()
    {
        $title = $this->isStudentPage() ? 'ثبت حاضری شاگرد' : 'ثبت حاضری کارمند';

        return view('livewire.pages.biometric.attendance-create', $this->viewData())->layout('layouts.app', [
            'title' => $title,
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => $this->isStudentPage() ? 'حاضری شاگردان' : 'حاضری کارمندان', 'url' => route($this->isStudentPage() ? 'attendance.students' : 'attendance.staff')],
                ['label' => $title],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'personOptions' => $this->isStudentPage() ? OptionLists::students() : OptionLists::allEmployees(),
            'statusOptions' => OptionLists::attendanceStatuses(),
            'indexRoute' => route($this->isStudentPage() ? 'attendance.students' : 'attendance.staff'),
            'title' => $this->isStudentPage() ? 'ثبت حاضری شاگرد' : 'ثبت حاضری کارمند',
            'personLabel' => $this->isStudentPage() ? 'شاگرد' : 'کارمند',
        ];
    }

    private function personClass(): string
    {
        return $this->isStudentPage() ? Student::class : Employee::class;
    }

    private function isStudentPage(): bool
    {
        return request()->routeIs('attendance.students.create');
    }
}
