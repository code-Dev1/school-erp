<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">تنظیمات سیستم</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت تنظیم</h2></div>
        <x-ui.button variant="secondary" href="{{ route('settings.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="تنظیم" icon="cog-6-tooth"><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><x-ui.input label="کلید" name="form.key" wire:model="form.key" /><x-ui.input label="گروپ" name="form.group" wire:model="form.group" /></div><div class="mt-4"><x-ui.textarea label="مقدار" name="form.value" rows="3" wire:model="form.value" /></div></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('settings.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
