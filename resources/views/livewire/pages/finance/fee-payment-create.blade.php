<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مالی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت پرداخت فیس</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('fees.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="پرداخت فیس" icon="banknotes">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="شاگرد" name="form.student_id" :options="$studentOptions" placeholder="انتخاب کنید" wire:model="form.student_id" />
                <x-ui.select label="ساختار فیس" name="form.fee_structure_id" :options="$feeStructureOptions" placeholder="انتخاب کنید" wire:model="form.fee_structure_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="خودکار" wire:model="form.academic_year_id" />
                <x-ui.input type="number" label="مبلغ کل" name="form.amount" min="0" step="0.01" wire:model="form.amount" />
                <x-ui.input type="number" label="مبلغ پرداخت" name="form.amount_paid" min="0" step="0.01" wire:model="form.amount_paid" />
                <x-ui.input type="number" label="تخفیف" name="form.discount_amount" min="0" step="0.01" wire:model="form.discount_amount" />
                <x-ui.input type="number" label="تعداد ماه" name="form.months_count" min="1" max="12" wire:model="form.months_count" />
                <x-ui.input type="date" label="تاریخ سررسید" name="form.due_date" wire:model="form.due_date" />
                <x-ui.input type="date" label="تاریخ پرداخت" name="form.payment_date" wire:model="form.payment_date" />
                <x-ui.input type="date" label="از تاریخ" name="form.covers_from" wire:model="form.covers_from" />
                <x-ui.input type="date" label="تا تاریخ" name="form.covers_to" wire:model="form.covers_to" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
                <x-ui.input label="نمبر رسید" name="form.receipt_number" wire:model="form.receipt_number" />
                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
                </div>
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('fees.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
