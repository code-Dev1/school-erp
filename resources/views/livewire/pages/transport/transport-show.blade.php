<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ترانسپورت</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $transportService->vehicle_plate_number }}</h2>
        </div>
        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('transport.edit', $transportService) }}" wire:navigate>ویرایش</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('transport.index') }}" wire:navigate>برگشت</x-ui.button>
        </div>
    </section>

    <x-ui.card title="جزئیات ترانسپورت" icon="truck">
        <dl class="grid gap-4 text-sm md:grid-cols-2 xl:grid-cols-4">
            <div><dt class="text-slate-500">نوع موتر</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->vehicle_type ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">ظرفیت</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->vehicle_capacity }}</dd></div>
            <div><dt class="text-slate-500">راننده</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->driver_name }}</dd></div>
            <div><dt class="text-slate-500">تماس راننده</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->driver_phone ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">نمبر لایسنس</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->driver_license_number ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">معاش راننده</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->driver_monthly_salary }}</dd></div>
            <div><dt class="text-slate-500">مسیر</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->route_name }}</dd></div>
            <div><dt class="text-slate-500">فیس ماهانه</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $transportService->monthly_fee }}</dd></div>
        </dl>
    </x-ui.card>

    <x-ui.card title="شاگردان مربوط" icon="academic-cap">
        <div class="space-y-2 text-sm">
            @forelse ($transportService->assignments as $assignment)
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2 dark:border-slate-800">
                    <span>{{ $assignment->student?->name }}</span>
                    <span class="text-slate-500">{{ $assignment->academicYear?->name ?: '-' }}</span>
                </div>
            @empty
                <p class="text-slate-500">هنوز شاگردی به این ترانسپورت وصل نشده است.</p>
            @endforelse
        </div>
    </x-ui.card>
</div>
