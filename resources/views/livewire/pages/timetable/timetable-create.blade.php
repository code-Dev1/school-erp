<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت آموزشی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت تقسیم اوقات</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('timetables.index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="درس" icon="calendar-days">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="صنف" name="form.class_id" :options="$classOptions" placeholder="انتخاب کنید" wire:model.live="form.class_id" />
                <x-ui.select label="بخش" name="form.section_id" :options="$sectionOptions" placeholder="انتخاب کنید" wire:model="form.section_id" />
                <x-ui.select label="مضمون" name="form.subject_id" :options="$subjectOptions" placeholder="انتخاب کنید" wire:model="form.subject_id" />
                <x-ui.select label="استاد" name="form.teacher_id" :options="$teacherOptions" placeholder="انتخاب کنید" wire:model="form.teacher_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="انتخاب کنید" wire:model="form.academic_year_id" />
                <x-ui.select label="روز" name="form.day_of_week" :options="$dayOptions" placeholder="انتخاب کنید" wire:model="form.day_of_week" />
                <x-ui.input type="time" label="شروع" name="form.start_time" wire:model="form.start_time" />
                <x-ui.input type="time" label="ختم" name="form.end_time" wire:model="form.end_time" />
                <x-ui.input label="اتاق" name="form.room" wire:model="form.room" />
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('timetables.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره تقسیم اوقات</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
