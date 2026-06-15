<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">استادان و کارمندان</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">کارمندان</h2>
        </div>

        <x-ui.button href="{{ route('staff.create') }}" icon="plus" wire:navigate>
            ثبت کارمند
        </x-ui.button>
    </section>

    @if (session('status'))
        <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            <x-ui.input type="search" label="جستجو" name="search" placeholder="نام، شماره، وظیفه یا دیپارتمنت" wire:model.live.debounce.300ms="search" />
            <x-ui.select label="وضعیت" name="status" :options="$statusOptions" placeholder="همه وضعیت ها" wire:model.live="status" />
            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">پاک سازی</x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">شماره</th>
                        <th class="px-4 py-3 text-right font-semibold">نام کارمند</th>
                        <th class="px-4 py-3 text-right font-semibold">وظیفه</th>
                        <th class="px-4 py-3 text-right font-semibold">مدیر مستقیم</th>
                        <th class="px-4 py-3 text-right font-semibold">تماس</th>
                        <th class="px-4 py-3 text-right font-semibold">وضعیت</th>
                        <th class="px-4 py-3 text-left font-semibold">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($staffMembers as $staff)
                        @php
                            $status = $staff->status instanceof \BackedEnum ? $staff->status->value : $staff->status;
                        @endphp

                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $staff->employee_id }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $staff->name ?: trim($staff->first_name.' '.$staff->last_name) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $staff->custom_job_title ?: $staff->job_title }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $staff->manager?->name ?? 'ثبت نشده' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $staff->phone ?: 'ثبت نشده' }}</td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <x-ui.badge variant="{{ $status === 'active' ? 'success' : 'neutral' }}">{{ $statusOptions[$status] ?? $status }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end">
                                    <x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $staff->id }})" wire:confirm="این کارمند حذف شود؟">
                                        حذف
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">کارمندی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($staffMembers->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $staffMembers->links() }}</div>
        @endif
    </section>
</div>
