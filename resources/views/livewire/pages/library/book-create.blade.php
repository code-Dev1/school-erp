<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">کتابخانه و منابع</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت کتاب</h2></div>
        <x-ui.button variant="secondary" href="{{ route('library.books.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="کتاب" icon="book-open">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="عنوان" name="form.title" wire:model="form.title" />
                <x-ui.input label="نویسنده" name="form.author" wire:model="form.author" />
                <x-ui.input label="ISBN" name="form.isbn" wire:model="form.isbn" />
                <x-ui.input label="دسته" name="form.category" wire:model="form.category" />
                <x-ui.input type="number" label="کل نسخه ها" name="form.total_copies" min="1" wire:model="form.total_copies" />
                <x-ui.input type="number" label="نسخه موجود" name="form.available_copies" min="0" wire:model="form.available_copies" />
                <x-ui.input label="رف" name="form.shelf" wire:model="form.shelf" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
            </div>
        </x-ui.card>
        <x-ui.card title="یادداشت" icon="pencil-square"><x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" /></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('library.books.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
