<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">حاضری و بیومتریک</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>
        <x-ui.button href="{{ $createRoute }}" icon="plus" wire:navigate>ثبت حاضری</x-ui.button>
    </section>

    @if (session('status')) <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert> @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[220px_220px_auto] md:items-end">
            <x-ui.input type="date" label="تاریخ" name="date" wire:model.live="date" />
            <x-ui.select label="وضعیت" name="status" :options="$statusOptions" placeholder="همه" wire:model.live="status" />
            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">پاک سازی</x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">نام</th>
                        <th class="px-4 py-3 text-right font-semibold">تاریخ</th>
                        <th class="px-4 py-3 text-right font-semibold">وضعیت</th>
                        <th class="px-4 py-3 text-right font-semibold">ورود</th>
                        <th class="px-4 py-3 text-right font-semibold">خروج</th>
                        <th class="px-4 py-3 text-left font-semibold">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($attendanceRows as $row)
                        @php
                            $status = $row->status instanceof \BackedEnum ? $row->status->value : $row->status;
                            $personName = $row->person?->name ?: trim(($row->person?->first_name ?? '').' '.($row->person?->last_name ?? ''));
                        @endphp
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $personName ?: 'ثبت نشده' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ \App\Support\School\JalaliDate::format($row->date) }}</td>
                            <td class="whitespace-nowrap px-4 py-4"><x-ui.badge variant="{{ $status === 'present' ? 'success' : ($status === 'absent' ? 'danger' : 'warning') }}">{{ $statusOptions[$status] ?? $status }}</x-ui.badge></td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $row->check_in ?: 'ثبت نشده' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $row->check_out ?: 'ثبت نشده' }}</td>
                            <td class="px-4 py-4"><div class="flex justify-end"><x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $row->id }})" wire:confirm="این حاضری حذف شود؟">حذف</x-ui.button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">حاضری ثبت نشده است.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attendanceRows->hasPages()) <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $attendanceRows->links() }}</div> @endif
    </section>
</div>
