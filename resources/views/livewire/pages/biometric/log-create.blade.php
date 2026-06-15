<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">حاضری و بیومتریک</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت لاگ بیومتریک</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('biometric.logs.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="لاگ" icon="finger-print">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input type="number" label="Biometric UID" name="form.biometric_uid" min="1" wire:model="form.biometric_uid" />
                <x-ui.select label="دستگاه" name="form.device_id" :options="$deviceOptions" placeholder="انتخاب کنید" wire:model="form.device_id" />
                <x-ui.input type="datetime-local" label="زمان" name="form.timestamp" wire:model="form.timestamp" />
                <x-ui.select label="نوع" name="form.log_type" :options="$typeOptions" placeholder="انتخاب کنید" wire:model="form.log_type" />
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('biometric.logs.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره لاگ</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
