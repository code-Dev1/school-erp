<?php

namespace App\Livewire\Pages\Timetable;

use App\Enums\Academic\DayOfWeek;
use App\Models\Timetable;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TimetableCreate extends Component
{
    public array $form = [
        'class_id' => '',
        'section_id' => '',
        'subject_id' => '',
        'teacher_id' => '',
        'academic_year_id' => '',
        'day_of_week' => '',
        'start_time' => '',
        'end_time' => '',
        'room' => '',
    ];

    public function save()
    {
        $validated = $this->validate()['form'];

        Timetable::create([
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room' => $validated['room'] ?: null,
        ]);

        session()->flash('status', 'تقسیم اوقات با موفقیت ثبت شد.');

        return redirect()->route('timetables.index');
    }

    protected function rules(): array
    {
        return [
            'form.class_id' => ['required', 'exists:classes,id'],
            'form.section_id' => ['required', 'exists:sections,id'],
            'form.subject_id' => ['required', 'exists:subjects,id'],
            'form.teacher_id' => ['required', 'exists:employees,id'],
            'form.academic_year_id' => ['required', 'exists:academic_years,id'],
            'form.day_of_week' => ['required', Rule::in(array_column(DayOfWeek::cases(), 'value'))],
            'form.start_time' => [
                'required',
                'date_format:H:i',
                Rule::unique('timetables', 'start_time')->where(fn ($query) => $query
                    ->where('class_id', $this->form['class_id'])
                    ->where('section_id', $this->form['section_id'])
                    ->where('academic_year_id', $this->form['academic_year_id'])
                    ->where('day_of_week', $this->form['day_of_week'])),
            ],
            'form.end_time' => ['required', 'date_format:H:i', 'after:form.start_time'],
            'form.room' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.timetable.timetable-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت تقسیم اوقات',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'تقسیم اوقات', 'url' => route('timetables.index')],
                ['label' => 'ثبت تقسیم اوقات'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'classOptions' => OptionLists::academicClasses(),
            'sectionOptions' => OptionLists::sections($this->form['class_id'] ?? null),
            'subjectOptions' => OptionLists::subjects(),
            'teacherOptions' => OptionLists::teachers(),
            'academicYearOptions' => OptionLists::academicYears(),
            'dayOptions' => OptionLists::daysOfWeek(),
        ];
    }
}
