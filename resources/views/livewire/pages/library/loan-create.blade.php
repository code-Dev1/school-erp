<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">کتابخانه و منابع</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت امانت کتاب</h2></div>
        <x-ui.button variant="secondary" href="{{ route('library.loans.index') }}" icon="chevron-right" wire:navigate>برگشت به لیست</x-ui.button>
    </section>
    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.</x-ui.alert> @endif
    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="امانت" icon="arrow-path">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="کتاب" name="form.library_book_id" :options="$bookOptions" placeholder="انتخاب کنید" wire:model="form.library_book_id" />
                <x-ui.select label="نوع امانت گیرنده" name="form.borrower_type" :options="$borrowerTypeOptions" placeholder="انتخاب کنید" wire:model.live="form.borrower_type" />
                @if (($form['borrower_type'] ?? '') === 'student')
                    <x-ui.select label="شاگرد" name="form.student_id" :options="$studentOptions" placeholder="انتخاب کنید" wire:model="form.student_id" />
                @else
                    <x-ui.select label="کارمند" name="form.employee_id" :options="$employeeOptions" placeholder="انتخاب کنید" wire:model="form.employee_id" />
                @endif
                <x-ui.input type="date" label="تاریخ امانت" name="form.borrowed_at" wire:model="form.borrowed_at" />
                <x-ui.input type="date" label="تاریخ برگشت" name="form.due_at" wire:model="form.due_at" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
            </div>
        </x-ui.card>
        <x-ui.card title="یادداشت" icon="pencil-square"><x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" /></x-ui.card>
        <div class="flex justify-end gap-3"><x-ui.button variant="secondary" href="{{ route('library.loans.index') }}" wire:navigate>لغو</x-ui.button><x-ui.button type="submit" icon="check">ذخیره</x-ui.button></div>
    </form>
</div>
