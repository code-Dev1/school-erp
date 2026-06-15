<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ترانسپورت</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $assignment->student?->name }}</h2>
        </div>
        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('transport.assignments.edit', $assignment) }}" wire:navigate>ویرایش</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('transport.assignments.index') }}" wire:navigate>برگشت</x-ui.button>
        </div>
    </section>

    <x-ui.card title="جزئیات ترانسپورت شاگرد" icon="link">
        <dl class="grid gap-4 text-sm md:grid-cols-2 xl:grid-cols-4">
            <div><dt class="text-slate-500">شاگرد</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->student?->name }}</dd></div>
            <div><dt class="text-slate-500">موتر</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->transportService?->vehicle_plate_number }}</dd></div>
            <div><dt class="text-slate-500">راننده</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->transportService?->driver_name ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">مسیر</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->transportService?->route_name }}</dd></div>
            <div><dt class="text-slate-500">فیس</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->fee_amount }}</dd></div>
            <div><dt class="text-slate-500">تاریخ شروع</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->starts_at ? \App\Support\School\JalaliDate::format($assignment->starts_at) : '-' }}</dd></div>
            <div><dt class="text-slate-500">تاریخ ختم</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->ends_at ? \App\Support\School\JalaliDate::format($assignment->ends_at) : '-' }}</dd></div>
            <div><dt class="text-slate-500">وضعیت</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $assignment->status }}</dd></div>
        </dl>
    </x-ui.card>
</div>
