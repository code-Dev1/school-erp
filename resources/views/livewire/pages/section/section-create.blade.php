<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت آموزشی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت بخش</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('sections.index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معلومات بخش" icon="rectangle-stack">
            <div class="grid gap-4 md:grid-cols-2">
                <x-ui.input
                    label="نام بخش"
                    name="form.name"
                    placeholder="مثلا الف"
                    wire:model="form.name"
                />

                <label class="flex items-center gap-3 pt-7 text-sm font-medium text-slate-700 dark:text-slate-200">
                    <input
                        type="checkbox"
                        class="rounded border-slate-300 text-primary-600 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-900"
                        wire:model="form.is_active"
                    >
                    بخش فعال باشد
                </label>
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('sections.index') }}" wire:navigate>
                لغو
            </x-ui.button>

            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره بخش</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>