<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مالی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت مصرف</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('expenses.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="مصرف" icon="banknotes">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="عنوان" name="form.title" wire:model="form.title" />
                <x-ui.select label="دسته" name="form.category" :options="$categoryOptions" placeholder="انتخاب کنید" wire:model="form.category" />
                <x-ui.input type="number" label="مبلغ" name="form.amount" min="0" step="0.01" wire:model="form.amount" />
                <x-ui.input type="date" label="تاریخ" name="form.date" wire:model="form.date" />
                <x-ui.input label="پرداخت کننده" name="form.paid_by" wire:model="form.paid_by" />
            </div>
        </x-ui.card>
        <x-ui.card title="توضیحات" icon="pencil-square"><div class="grid gap-4 md:grid-cols-2"><x-ui.textarea label="توضیحات" name="form.description" rows="3" wire:model="form.description" /><x-ui.textarea label="یادداشت" name="form.notes" rows="3" wire:model="form.notes" /></div></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('expenses.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
