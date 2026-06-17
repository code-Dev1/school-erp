<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ویرایش استاد</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">ویرایش معلومات استاد</h2>
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

    <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
        <x-ui.card title="معلومات هویتی" icon="identification">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نام" name="form.first_name" wire:model="form.first_name" />
                <x-ui.input label="تخلص" name="form.last_name" wire:model="form.last_name" />
                <x-ui.input label="نام پدر" name="form.father_name" wire:model="form.father_name" />
                <x-ui.input label="نام پدرکلان" name="form.grandfather_name" wire:model="form.grandfather_name" />
                <x-ui.input label="نمبر تذکره" name="form.tazkira_number" wire:model="form.tazkira_number" />
                <x-ui.select label="جنسیت" name="form.gender" :options="$genderOptions" placeholder="انتخاب کنید" wire:model="form.gender" />
                <x-ui.input type="date" label="تاریخ تولد" name="form.date_of_birth" wire:model="form.date_of_birth" />
                <div class="col-span-1 xl:col-span-4">
                    <x-ui.card title="عکس استاد" icon="photo" class="bg-slate-50/50">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="space-y-4">
                                @if ($teacher->photo_path)
                                    <img src="{{ Storage::disk('public')->url($teacher->photo_path) }}" alt="عکس استاد" class="h-32 w-32 rounded-xl object-cover border border-slate-200" />
                                @else
                                    <div class="flex h-32 w-32 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-100 text-slate-500">
                                        عکس موجود نیست
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <x-ui.file-upload
                                    label="بارگذاری عکس جدید"
                                    name="photo"
                                    accept="image/*"
                                    imagePreview
                                    wire:model="photo"
                                />

                                <div class="flex items-center gap-2">
                                    <input id="removePhoto" type="checkbox" wire:model="removePhoto" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                                    <label for="removePhoto" class="text-sm text-slate-600 dark:text-slate-300">حذف عکس فعلی</label>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
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
                <span wire:loading.remove wire:target="save">به‌روزرسانی استاد</span>
                <span wire:loading wire:target="save">در حال به‌روزرسانی...</span>
            </x-ui.button>
        </div>
    </form>
</div>
