<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت شاگردان</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت سرپرست</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('guardian-index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معلومات هویتی" subtitle="مشخصات اصلی سرپرست شاگرد" icon="users">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نام مکمل" name="form.name" wire:model="form.name" />
                <x-ui.input label="نام پدر" name="form.father_name" wire:model="form.father_name" />
                <x-ui.input label="نمبر تذکره" name="form.tazkira_number" wire:model="form.tazkira_number" />
            </div>
        </x-ui.card>

        <x-ui.card title="تماس و وظیفه" icon="phone">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نمبر تماس" name="form.phone" wire:model="form.phone" />
                <x-ui.input label="واتساپ" name="form.whatsapp_number" wire:model="form.whatsapp_number" />
                <x-ui.input type="email" label="ایمیل" name="form.email" wire:model="form.email" />
                <x-ui.input label="وظیفه" name="form.job" wire:model="form.job" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
            </div>
        </x-ui.card>

        <x-ui.card title="آدرس و یادداشت" icon="identification">
            <div class="grid gap-4 md:grid-cols-2">
                <x-ui.textarea label="آدرس" name="form.address" rows="3" wire:model="form.address" />
                <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
            </div>
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('guardian-index') }}" wire:navigate>
                لغو
            </x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره سرپرست</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
