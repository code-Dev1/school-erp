<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ترانسپورت</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('transport.assignments.index') }}" icon="chevron-right" wire:navigate>برگشت</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا فورم را بررسی کنید.">بعضی فیلدها درست تکمیل نشده‌اند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="ارتباط شاگرد با ترانسپورت" icon="link">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="شاگرد" name="form.student_id" :options="$studentOptions" placeholder="شاگرد را انتخاب کنید" wire:model="form.student_id" />
                <x-ui.select label="ترانسپورت" name="form.transport_service_id" :options="$transportOptions" placeholder="موتر / راننده / مسیر" wire:model="form.transport_service_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="اختیاری" wire:model="form.academic_year_id" />
                <x-ui.input type="number" label="فیس" name="form.fee_amount" min="0" step="0.01" wire:model="form.fee_amount" />
                <x-ui.input type="date" label="تاریخ شروع" name="form.starts_at" wire:model="form.starts_at" />
                <x-ui.input type="date" label="تاریخ ختم" name="form.ends_at" wire:model="form.ends_at" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" wire:model="form.status" />
                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
                </div>
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="secondary" href="{{ route('transport.assignments.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check">ذخیره</x-ui.button>
        </div>
    </form>
</div>
