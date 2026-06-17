<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">جزئیات کارمند</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">{{ $staff->name ?: $staff->employee_id }}</h2>
        </div>

        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('staff.edit', $staff) }}" wire:navigate>ویرایش</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('staff.index') }}" wire:navigate>بازگشت</x-ui.button>
        </div>
    </section>

    <section class="grid gap-4 lg:grid-cols-2">
        <x-ui.card title="اطلاعات پایه" icon="user-circle">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">نام کامل</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->name }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">شماره کارمند</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->employee_id }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">وظیفه</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->job_title ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">بخش</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->department ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="عکس کارمند" icon="photo" class="bg-slate-50/50">
            @if ($staff->photo_path)
                <img src="{{ Storage::disk('public')->url($staff->photo_path) }}" alt="عکس کارمند" class="h-44 w-full rounded-2xl object-cover border border-slate-200" />
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
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->email ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">تلفن</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->phone ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">واتساپ</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->whatsapp_number ?: '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">گزارش به</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->manager?->name ?: '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>

        <x-ui.card title="اطلاعات قرارداد" icon="document-text">
            <dl class="grid gap-3 text-sm">
                <div>
                    <dt class="text-slate-500">نوع قرارداد</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->contract_type?->value ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">پایه حقوق</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->base_salary ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">تاریخ استخدام</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->hired_at?->format('Y-m-d') ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="text-slate-500">وضعیت</dt>
                    <dd class="font-semibold text-slate-950 dark:text-white">{{ $staff->status?->value ?? '-' }}</dd>
                </div>
            </dl>
        </x-ui.card>
    </section>
</div>
