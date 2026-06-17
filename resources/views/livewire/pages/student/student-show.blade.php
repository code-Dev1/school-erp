<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">جزئیات شاگرد</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">{{ $student->name ?: $student->asas_number }}</h2>
        </div>

        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('student.edit', $student) }}" wire:navigate>ویرایش</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('student-index') }}" wire:navigate>بازگشت</x-ui.button>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <x-ui.card title="اطلاعات پایه" icon="user-circle">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">نام کامل</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->name }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">نمبر اساس</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->asas_number }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">شماره تذکره</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->tazkira_number ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">جنسیت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->gender?->value ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">تاریخ تولد</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->date_of_birth?->format('Y-m-d') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">صنف و سیکشن</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->academicClass?->name ?? '-' }} {{ $student->section?->name ? '/ '.$student->section->name : '' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="عکس شاگرد" icon="photo" class="bg-slate-50/50">
            @if ($student->photo_path)
                <img src="{{ Storage::disk('public')->url($student->photo_path) }}" alt="عکس شاگرد" class="h-44 w-full rounded-2xl object-cover border border-slate-200" />
            @else
                <div class="flex min-h-[11rem] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-slate-500">
                    عکس ثبت نشده است.
                </div>
            @endif
        </x-ui.card>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <x-ui.card title="اطلاعات تماس" icon="phone">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">شماره تماس</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->contact_number ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">پدر</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->father_name ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">والدین</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->primaryGuardian->first()?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">آدرس</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->address ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="وضعیت تحصیلی" icon="academic-cap">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">سال تحصیلی</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->academicYear?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">وضعیت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->status?->value ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">تاریخ ثبت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->admission_date?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">یادداشت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $student->note ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>
    </section>
</div>
