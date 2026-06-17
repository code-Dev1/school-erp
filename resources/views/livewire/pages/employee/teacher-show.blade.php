<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">جزئیات استاد</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">{{ $teacher->name ?: $teacher->employee_id }}</h2>
        </div>

        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('teachers.edit', $teacher) }}" wire:navigate>ویرایش</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('teachers.index') }}" wire:navigate>بازگشت</x-ui.button>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <x-ui.card title="اطلاعات پایه" icon="user-circle">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">نام کامل</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->name }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">شماره کارمند</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->employee_id }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">وظیفه</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->job_title ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">بخش</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->department ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="عکس استاد" icon="photo" class="bg-slate-50/50">
            @if ($teacher->photo_path)
                <img src="{{ Storage::disk('public')->url($teacher->photo_path) }}" alt="عکس استاد" class="h-44 w-full rounded-2xl object-cover border border-slate-200" />
            @else
                <div class="flex min-h-[11rem] items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 text-slate-500">
                    عکس ثبت نشده است.
                </div>
            @endif
        </x-ui.card>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <x-ui.card title="تماس" icon="phone">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">پست الکترونیک</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->email ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">تلفن</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->phone ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">واتساپ</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->whatsapp_number ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">گزارش به</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->manager?->name ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="اطلاعات قرارداد" icon="document-text">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">نوع قرارداد</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->contract_type?->value ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">پایه حقوق</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->base_salary ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">تاریخ استخدام</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->hired_at?->format('Y-m-d') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">وضعیت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $teacher->status?->value ?? '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>
    </section>
</div>
