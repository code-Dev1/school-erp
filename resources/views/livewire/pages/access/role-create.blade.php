<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">تنظیمات سیستم</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت نقش</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('roles.index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="نقش" icon="shield-check">
            <x-ui.input label="نام نقش" name="form.name" wire:model="form.name" />
        </x-ui.card>

        <x-ui.card title="صلاحیت ها" icon="lock-closed">
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($permissionOptions as $permission)
                    <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-white p-3 text-sm dark:border-slate-800 dark:bg-slate-950">
                        <input type="checkbox" value="{{ $permission }}" wire:model="form.permissions" class="mt-1 rounded border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950">
                        <span class="font-medium text-slate-800 dark:text-slate-100">{{ $permission }}</span>
                    </label>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400">ابتدا یک صلاحیت ثبت کنید.</p>
                @endforelse
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('roles.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره نقش</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
