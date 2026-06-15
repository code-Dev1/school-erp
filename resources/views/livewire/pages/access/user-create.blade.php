<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">تنظیمات سیستم</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت کاربر</h2></div>
        <x-ui.button variant="secondary" href="{{ route('users.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="کاربر" icon="user"><div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"><x-ui.input label="نام" name="form.name" wire:model="form.name" /><x-ui.input type="email" label="ایمیل" name="form.email" wire:model="form.email" /><x-ui.input type="password" label="رمز عبور" name="form.password" wire:model="form.password" /><x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" wire:model="form.status" /></div></x-ui.card>
        <x-ui.card title="نقش ها" icon="shield-check"><div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">@foreach ($roleOptions as $role)<label class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-sm dark:border-slate-800 dark:bg-slate-950"><input type="checkbox" value="{{ $role }}" wire:model="form.roles" class="rounded border-slate-300 text-slate-950"><span class="font-medium text-slate-800 dark:text-slate-100">{{ $role }}</span></label>@endforeach</div></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('users.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
