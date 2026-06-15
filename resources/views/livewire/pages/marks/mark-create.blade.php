<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">امتحانات</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت نمره</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('marks.index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="شاگرد و مضمون" icon="academic-cap">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="صنف" name="form.class_id" :options="$classOptions" placeholder="انتخاب کنید" wire:model.live="form.class_id" />
                <x-ui.select label="بخش" name="form.section_id" :options="$sectionOptions" placeholder="انتخاب کنید" wire:model.live="form.section_id" />
                <x-ui.select label="شاگرد" name="form.student_id" :options="$studentOptions" placeholder="انتخاب کنید" wire:model.live="form.student_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="انتخاب کنید" wire:model="form.academic_year_id" />
                <x-ui.select label="مضمون" name="form.subject_id" :options="$subjectOptions" placeholder="انتخاب کنید" wire:model="form.subject_id" />
                <x-ui.select label="استاد" name="form.teacher_id" :options="$teacherOptions" placeholder="انتخاب کنید" wire:model="form.teacher_id" />
            </div>
        </x-ui.card>

        <x-ui.card title="امتحان و نمره" icon="document-chart-bar">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="دوره" name="form.term" :options="$termOptions" placeholder="انتخاب کنید" wire:model="form.term" />
                <x-ui.input label="سمستر" name="form.semester" wire:model="form.semester" />
                <x-ui.input label="نام امتحان" name="form.exam_name" wire:model="form.exam_name" />
                <x-ui.select label="نوع امتحان" name="form.exam_type" :options="$examTypeOptions" placeholder="انتخاب کنید" wire:model="form.exam_type" />
                <x-ui.input type="number" label="نمره گرفته شده" name="form.marks_obtained" min="0" step="0.01" wire:model="form.marks_obtained" />
                <x-ui.input type="number" label="نمره مجموعی" name="form.total_marks" min="1" step="0.01" wire:model="form.total_marks" />
                <x-ui.input type="date" label="تاریخ نتیجه" name="form.result_date" wire:model="form.result_date" />
                <x-ui.input type="date" label="تاریخ امتحان" name="form.exam_date" wire:model="form.exam_date" />
            </div>
        </x-ui.card>

        <x-ui.card title="یادداشت" icon="pencil-square">
            <div class="grid gap-4 md:grid-cols-2">
                <x-ui.textarea label="ملاحظات" name="form.remarks" rows="3" wire:model="form.remarks" />
                <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('marks.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره نمره</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
