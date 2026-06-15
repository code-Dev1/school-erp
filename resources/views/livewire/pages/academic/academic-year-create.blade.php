<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت آموزشی</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت سال تعلیمی</h2></div>
        <x-ui.button variant="secondary" href="{{ route('academic-years.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="سال تعلیمی" icon="calendar-days"><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><x-ui.input label="نام سال" name="form.name" wire:model="form.name" /><x-ui.input type="date" label="شروع" name="form.starts_at" wire:model="form.starts_at" /><x-ui.input type="date" label="ختم" name="form.ends_at" wire:model="form.ends_at" /><label class="flex items-center gap-2 pt-7 text-sm font-medium text-slate-700 dark:text-slate-200"><input type="checkbox" wire:model="form.is_active" class="rounded border-slate-300"> فعال</label></div></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('academic-years.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
