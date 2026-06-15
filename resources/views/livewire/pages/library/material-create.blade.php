<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">کتابخانه و منابع</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت مواد درسی</h2></div>
        <x-ui.button variant="secondary" href="{{ route('library.materials.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="مواد درسی" icon="rectangle-stack">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="عنوان" name="form.title" wire:model="form.title" />
                <x-ui.select label="نوع" name="form.type" :options="$typeOptions" placeholder="انتخاب کنید" wire:model="form.type" />
                <x-ui.select label="صنف" name="form.class_id" :options="$classOptions" placeholder="عمومی" wire:model="form.class_id" />
                <x-ui.select label="مضمون" name="form.subject_id" :options="$subjectOptions" placeholder="عمومی" wire:model="form.subject_id" />
                <x-ui.select label="استاد" name="form.teacher_id" :options="$teacherOptions" placeholder="انتخاب کنید" wire:model="form.teacher_id" />
                <x-ui.input label="مسیر فایل" name="form.file_path" wire:model="form.file_path" />
                <x-ui.input type="url" label="لینک" name="form.external_url" wire:model="form.external_url" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
            </div>
        </x-ui.card>
        <x-ui.card title="توضیحات" icon="pencil-square"><x-ui.textarea label="توضیحات" name="form.description" rows="3" wire:model="form.description" /></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('library.materials.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
