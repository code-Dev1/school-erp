<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مالی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت ساختار فیس</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('fees.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="ساختار فیس" icon="currency-dollar">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="صنف" name="form.class_id" :options="$classOptions" placeholder="انتخاب کنید" wire:model="form.class_id" />
                <x-ui.select label="نوع فیس" name="form.fee_type_id" :options="$feeTypeOptions" placeholder="انتخاب کنید" wire:model="form.fee_type_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="انتخاب کنید" wire:model="form.academic_year_id" />
                <x-ui.input type="number" label="مبلغ" name="form.amount" min="0" step="0.01" wire:model="form.amount" />
                <x-ui.input type="number" label="روز سررسید" name="form.due_day" min="1" max="31" wire:model="form.due_day" />
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('fees.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
