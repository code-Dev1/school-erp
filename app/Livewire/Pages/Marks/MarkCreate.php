<?php

namespace App\Livewire\Pages\Marks;

use App\Models\Student;
use App\Models\StudentResult;
use App\Support\School\OptionLists;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MarkCreate extends Component
{
    public array $form = [
        'student_id' => '',
        'class_id' => '',
        'section_id' => '',
        'subject_id' => '',
        'teacher_id' => '',
        'academic_year_id' => '',
        'term' => '',
        'semester' => '',
        'exam_name' => '',
        'exam_type' => '',
        'marks_obtained' => '',
        'total_marks' => '100',
        'result_date' => '',
        'exam_date' => '',
        'remarks' => '',
        'note' => '',
    ];

    public function mount(): void
    {
        $this->form['result_date'] = now()->format('Y-m-d');
    }

    public function updatedFormStudentId($studentId): void
    {
        $student = Student::query()->find($studentId);

        if (! $student) {
            return;
        }

        $this->form['class_id'] = (string) $student->class_id;
        $this->form['section_id'] = (string) $student->section_id;
        $this->form['academic_year_id'] = (string) $student->academic_year_id;
    }

    public function save()
    {
        $validated = $this->validate()['form'];

        StudentResult::create([
            'student_id' => $validated['student_id'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'] ?: null,
            'academic_year_id' => $validated['academic_year_id'],
            'term' => $validated['term'],
            'semester' => $validated['semester'] ?: null,
            'exam_name' => $validated['exam_name'],
            'exam_type' => $validated['exam_type'] ?: null,
            'marks_obtained' => $validated['marks_obtained'],
            'total_marks' => $validated['total_marks'],
            'grade' => $this->grade((float) $validated['marks_obtained'], (float) $validated['total_marks']),
            'remarks' => $validated['remarks'] ?: null,
            'note' => $validated['note'] ?: null,
            'recorded_by' => auth()->id(),
            'result_date' => $validated['result_date'],
            'exam_date' => $validated['exam_date'] ?: null,
        ]);

        session()->flash('status', 'نمره با موفقیت ثبت شد.');

        return redirect()->route('marks.index');
    }

    protected function rules(): array
    {
        return [
            'form.student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('student_results', 'student_id')->where(fn ($query) => $query
                    ->where('subject_id', $this->form['subject_id'])
                    ->where('academic_year_id', $this->form['academic_year_id'])
                    ->where('term', $this->form['term'])
                    ->where('exam_name', $this->form['exam_name'])),
            ],
            'form.class_id' => ['required', 'exists:classes,id'],
            'form.section_id' => ['required', 'exists:sections,id'],
            'form.subject_id' => ['required', 'exists:subjects,id'],
            'form.teacher_id' => ['nullable', 'exists:employees,id'],
            'form.academic_year_id' => ['required', 'exists:academic_years,id'],
            'form.term' => ['required', 'string', 'max:255'],
            'form.semester' => ['nullable', 'string', 'max:255'],
            'form.exam_name' => ['required', 'string', 'max:255'],
            'form.exam_type' => ['nullable', 'string', 'max:255'],
            'form.marks_obtained' => ['required', 'numeric', 'min:0', 'lte:form.total_marks'],
            'form.total_marks' => ['required', 'numeric', 'min:1'],
            'form.result_date' => ['required', 'date'],
            'form.exam_date' => ['nullable', 'date'],
            'form.remarks' => ['nullable', 'string'],
            'form.note' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.marks.mark-create', $this->viewData())->layout('layouts.app', [
            'title' => 'ثبت نمره',
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'نمرات و نتایج', 'url' => route('marks.index')],
                ['label' => 'ثبت نمره'],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'studentOptions' => OptionLists::students($this->form['class_id'] ?? null, $this->form['section_id'] ?? null),
            'classOptions' => OptionLists::academicClasses(),
            'sectionOptions' => OptionLists::sections($this->form['class_id'] ?? null),
            'subjectOptions' => OptionLists::subjects(),
            'teacherOptions' => OptionLists::teachers(),
            'academicYearOptions' => OptionLists::academicYears(),
            'termOptions' => OptionLists::terms(),
            'examTypeOptions' => OptionLists::examTypes(),
        ];
    }

    private function grade(float $marks, float $total): string
    {
        $percentage = $total > 0 ? ($marks / $total) * 100 : 0;

        return match (true) {
            $percentage >= 90 => 'A',
            $percentage >= 80 => 'B',
            $percentage >= 70 => 'C',
            $percentage >= 60 => 'D',
            default => 'F',
        };
    }
}
