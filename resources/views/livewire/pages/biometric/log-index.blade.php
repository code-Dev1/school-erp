<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">حاضری و بیومتریک</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">لاگ های بیومتریک</h2>
        </div>
        <x-ui.button href="{{ route('biometric.logs.create') }}" icon="plus" wire:navigate>ثبت لاگ</x-ui.button>
    </section>

    @if (session('status')) <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert> @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            <x-ui.select label="دستگاه" name="deviceId" :options="$deviceOptions" placeholder="همه دستگاه ها" wire:model.live="deviceId" />
            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">پاک سازی</x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">UID</th>
                        <th class="px-4 py-3 text-right font-semibold">دستگاه</th>
                        <th class="px-4 py-3 text-right font-semibold">نوع</th>
                        <th class="px-4 py-3 text-right font-semibold">زمان</th>
                        <th class="px-4 py-3 text-right font-semibold">همگام</th>
                        <th class="px-4 py-3 text-left font-semibold">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($logs as $log)
                        @php $type = $log->log_type instanceof \BackedEnum ? $log->log_type->value : $log->log_type; @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $log->biometric_uid }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $log->device?->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $typeOptions[$type] ?? $type }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ \App\Support\School\JalaliDate::format($log->timestamp) }} {{ $log->timestamp?->format('H:i') }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $log->synced_at ? \App\Support\School\JalaliDate::format($log->synced_at).' '.$log->synced_at->format('H:i') : 'نه' }}</td>
                            <td class="px-4 py-4"><div class="flex justify-end"><x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $log->id }})" wire:confirm="این لاگ حذف شود؟">حذف</x-ui.button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">لاگی ثبت نشده است.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages()) <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $logs->links() }}</div> @endif
    </section>
</div>
