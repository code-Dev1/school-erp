<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">استادان و کارمندان</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ثبت استاد</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('teachers.index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معلومات هویتی" icon="identification">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نام" name="form.first_name" wire:model="form.first_name" />
                <x-ui.input label="تخلص" name="form.last_name" wire:model="form.last_name" />
                <x-ui.input label="نام پدر" name="form.father_name" wire:model="form.father_name" />
                <x-ui.input label="نام پدرکلان" name="form.grandfather_name" wire:model="form.grandfather_name" />
                <x-ui.input label="نمبر تذکره" name="form.tazkira_number" wire:model="form.tazkira_number" />
                <x-ui.select label="جنسیت" name="form.gender" :options="$genderOptions" placeholder="انتخاب کنید" wire:model="form.gender" />
                <x-ui.input type="date" label="تاریخ تولد" name="form.date_of_birth" wire:model="form.date_of_birth" />
            </div>
        </x-ui.card>

        <x-ui.card title="تماس و وظیفه" icon="phone">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نمبر تماس" name="form.phone" wire:model="form.phone" />
                <x-ui.input label="واتساپ" name="form.whatsapp_number" wire:model="form.whatsapp_number" />
                <x-ui.input type="email" label="ایمیل" name="form.email" wire:model="form.email" />
                <x-ui.select label="نوع استاد" name="form.teacher_type" :options="$teacherTypeOptions" placeholder="انتخاب کنید" wire:model="form.teacher_type" />
                <x-ui.input label="دیپارتمنت" name="form.department" wire:model="form.department" />
                <x-ui.input label="درجه تحصیل" name="form.education_level" wire:model="form.education_level" />
                <x-ui.input label="رشته تحصیلی" name="form.field_of_study" wire:model="form.field_of_study" />
            </div>
        </x-ui.card>

        <x-ui.card title="قرارداد" icon="banknotes">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input type="date" label="تاریخ استخدام" name="form.hired_at" wire:model="form.hired_at" />
                <x-ui.select label="نوع قرارداد" name="form.contract_type" :options="$contractTypeOptions" placeholder="انتخاب کنید" wire:model="form.contract_type" />
                <x-ui.input type="number" label="معاش اصلی" name="form.base_salary" min="0" step="0.01" wire:model="form.base_salary" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
            </div>
        </x-ui.card>

        <x-ui.card title="یادداشت" icon="pencil-square">
            <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
        </x-ui.card>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('teachers.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">ذخیره استاد</span>
                <span wire:loading wire:target="save">در حال ذخیره...</span>
            </x-ui.button>
        </div>
    </form>
</div>
