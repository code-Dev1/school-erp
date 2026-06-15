<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت آموزشی</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">صنف ها</h2>
        </div>

        <x-ui.button href="{{ route('classes.create') }}" icon="plus" wire:navigate>
            ثبت صنف
        </x-ui.button>
    </section>

    @if (session('status'))
        <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
            <x-ui.input type="search" label="جستجو" name="search" placeholder="نام صنف یا سال" wire:model.live.debounce.300ms="search" />
            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">پاک سازی</x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">نام صنف</th>
                        <th class="px-4 py-3 text-right font-semibold">درجه</th>
                        <th class="px-4 py-3 text-right font-semibold">بخش ها</th>
                        <th class="px-4 py-3 text-right font-semibold">شاگردان</th>
                        <th class="px-4 py-3 text-right font-semibold">مضامین</th>
                        <th class="px-4 py-3 text-right font-semibold">تقسیم اوقات</th>
                        <th class="px-4 py-3 text-left font-semibold">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($classes as $class)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $class->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $class->grade_level ?: 'ثبت نشده' }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ number_format($class->sections_count) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ number_format($class->students_count) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ number_format($class->subjects_count) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ number_format($class->timetables_count) }}</td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end">
                                    <x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $class->id }})" wire:confirm="این صنف حذف شود؟">
                                        حذف
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">صنفی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($classes->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $classes->links() }}</div>
        @endif
    </section>
</div>
