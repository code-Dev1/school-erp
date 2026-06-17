<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ویرایش شاگرد</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">ویرایش معلومات شاگرد</h2>
        </div>

        <x-ui.button variant="secondary" href="{{ route('student-index') }}" icon="chevron-right" wire:navigate>
            برگشت به لیست
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفا معلومات فورم را بررسی کنید.">
            بعضی فیلدهای ضروری تکمیل نشده یا درست نیستند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
        <x-ui.card title="معلومات اساسی" subtitle="شماره اساس و معلومات هویتی شاگرد" icon="academic-cap">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نمبر اساس" name="form.asas_number" wire:model="form.asas_number" />
                <x-ui.input label="نام" name="form.first_name" wire:model="form.first_name" />
                <x-ui.input label="تخلص" name="form.last_name" wire:model="form.last_name" />
                <x-ui.input label="نام پدر" name="form.father_name" wire:model="form.father_name" />
                <x-ui.input label="نام پدرکلان" name="form.grandfather_name" wire:model="form.grandfather_name" />
                <x-ui.input label="نمبر تذکره" name="form.tazkira_number" wire:model="form.tazkira_number" />
                <x-ui.select label="جنسیت" name="form.gender" :options="$genderOptions" placeholder="انتخاب کنید" wire:model="form.gender" />
                <x-ui.input type="date" label="تاریخ تولد" name="form.date_of_birth" wire:model="form.date_of_birth" />

                <div class="col-span-1 xl:col-span-4">
                    <x-ui.card title="عکس شاگرد" icon="photo" class="bg-slate-50/50">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="space-y-4">
                                @if ($student->photo_path)
                                    <img src="{{ Storage::disk('public')->url($student->photo_path) }}" alt="عکس شاگرد" class="h-32 w-32 rounded-xl object-cover border border-slate-200" />
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

        <x-ui.card title="معلومات آموزشی" subtitle="صنف، بخش و سال تعلیمی شاگرد" icon="book-open">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="صنف" name="form.class_id" :options="$classOptions" placeholder="انتخاب کنید" wire:model="form.class_id" />
                <x-ui.select label="بخش" name="form.section_id" :options="$sectionOptions" placeholder="انتخاب کنید" wire:model="form.section_id" />
                <x-ui.select label="سال تعلیمی" name="form.academic_year_id" :options="$academicYearOptions" placeholder="انتخاب کنید" wire:model="form.academic_year_id" />
                <x-ui.input type="date" label="تاریخ شمولیت" name="form.admission_date" wire:model="form.admission_date" />
                <x-ui.select label="نوع شاگرد" name="form.student_type" :options="$studentTypeOptions" placeholder="انتخاب کنید" wire:model="form.student_type" />
                <x-ui.input label="مکتب قبلی" name="form.previous_school" wire:model="form.previous_school" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" placeholder="انتخاب کنید" wire:model="form.status" />
                <div wire:ignore>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">سرپرست اصلی</label>
                    <select id="guardian-select" name="form.guardian_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">بدون سرپرست</option>
                        @foreach($guardianOptions as $id => $label)
                            <option value="{{ $id }}" @if((string)($form['guardian_id'] ?? '') === (string)$id) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.select label="رابطه با سرپرست" name="form.guardian_relationship" :options="$guardianRelationshipOptions" placeholder="انتخاب کنید" wire:model.live="form.guardian_relationship" />
                @if (($form['guardian_relationship'] ?? '') === '__custom')
                    <x-ui.input label="رابطه دلخواه" name="form.custom_guardian_relationship" placeholder="مثلا ماما، خاله، پدرکلان..." wire:model="form.custom_guardian_relationship" />
                @endif
            </div>
        </x-ui.card>

        <x-ui.card title="یادداشت" icon="phone">
            <div class="grid gap-4 md:grid-cols-1">
                <div class="md:col-span-1">
                    <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
                </div>
            </div>
        </x-ui.card>

        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        @endpush

        @push('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJ+Y3Q9v3Qk2a6VZ6bQb0Z6bQ5nI2m9Kp3r5o=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                document.addEventListener('livewire:load', function () {
                    const $select = $('#guardian-select');
                    if (!$select.length) return;

                    $select.select2({
                        placeholder: 'بدون سرپرست',
                        allowClear: true,
                        width: '100%'
                    });

                    $select.on('change', function () {
                        Livewire.emit('guardianChanged', $(this).val());
                    });

                    const initial = '{{ $form['guardian_id'] ?? '' }}';
                    if (initial) {
                        $select.val(initial).trigger('change');
                    }

                    Livewire.hook('message.processed', () => {
                        const val = '{{ $form['guardian_id'] ?? '' }}';
                        $select.val(val).trigger('change');
                    });
                });
            </script>
        @endpush

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <x-ui.button variant="secondary" href="{{ route('student-index') }}" wire:navigate>
                لغو
            </x-ui.button>
            <x-ui.button type="submit" icon="check" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">به‌روزرسانی شاگرد</span>
                <span wire:loading wire:target="save">در حال به‌روزرسانی...</span>
            </x-ui.button>
        </div>
    </form>
</div>
