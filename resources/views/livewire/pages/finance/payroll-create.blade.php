<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مالی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت معاش</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('payroll.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معاش" icon="currency-dollar">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="کارمند" name="form.employee_id" :options="$employeeOptions" placeholder="انتخاب کنید" wire:model.live="form.employee_id" />
                <x-ui.select label="ماه" name="form.month" :options="$monthOptions" placeholder="انتخاب کنید" wire:model="form.month" />
                <x-ui.input type="number" label="سال" name="form.year" min="1300" wire:model="form.year" />
                <x-ui.input type="number" label="معاش اصلی" name="form.base_salary" min="0" step="0.01" wire:model="form.base_salary" />
                <x-ui.input type="number" label="اضافات" name="form.total_allowances" min="0" step="0.01" wire:model="form.total_allowances" />
                <x-ui.input type="number" label="کسورات" name="form.total_deductions" min="0" step="0.01" wire:model="form.total_deductions" />
                <x-ui.input type="number" label="کسر غیرحاضری" name="form.absence_deduction" min="0" step="0.01" wire:model="form.absence_deduction" />
                <x-ui.input type="datetime-local" label="زمان پرداخت" name="form.paid_at" wire:model="form.paid_at" />
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('payroll.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
