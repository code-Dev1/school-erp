<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">مدیریت شاگردان</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">شاگردان</h2>
        </div>

        <x-ui.button href="{{ route('student-create') }}" icon="user-plus" wire:navigate>
            ثبت نام شاگرد
        </x-ui.button>
    </section>

    @if (session('status'))
        <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            <x-ui.input
                type="search"
                label="جستجو"
                name="search"
                placeholder="نام، نمبر اساس، تذکره یا تماس"
                wire:model.live.debounce.300ms="search"
            />

            <x-ui.select
                label="وضعیت"
                name="status"
                :options="$statusOptions"
                placeholder="همه وضعیت‌ها"
                wire:model.live="status"
            />

            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">
                پاک‌سازی
            </x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">نمبر اساس</th>
                        <th class="px-4 py-3 text-right font-semibold">نام شاگرد</th>
                        <th class="px-4 py-3 text-right font-semibold">نام پدر</th>
                        <th class="px-4 py-3 text-right font-semibold">صنف</th>
                        <th class="px-4 py-3 text-right font-semibold">سال</th>
                        <th class="px-4 py-3 text-right font-semibold">وضعیت</th>
                        <th class="px-4 py-3 text-left font-semibold">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($students as $student)
                        @php
                            $status = $student->status instanceof \BackedEnum ? $student->status->value : $student->status;
                            $statusLabel = $statusOptions[$status] ?? 'ثبت نشده';
                            $statusClass = match ($status) {
                                'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-200 dark:ring-emerald-500/30',
                                'transferred' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-500/30',
                                'graduated' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-500/10 dark:text-sky-200 dark:ring-sky-500/30',
                                'expelled' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-200 dark:ring-rose-500/30',
                                default => 'bg-slate-100 text-slate-600 ring-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:ring-slate-800',
                            };
                        @endphp

                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">
                                {{ $student->asas_number ?? $student->student_id }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">
                                {{ $student->name ?: trim($student->first_name.' '.$student->last_name) }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">
                                {{ $student->father_name ?: 'ثبت نشده' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">
                                {{ $student->academicClass?->name ?? 'ثبت نشده' }}
                                @if ($student->section?->name)
                                    <span class="text-slate-400">/ {{ $student->section->name }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">
                                {{ $student->academicYear?->name ?? 'ثبت نشده' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    @if (Route::has('student.show'))
                                        <x-ui.button
                                            type="button"
                                            size="sm"
                                            variant="primary"
                                            icon="eye"
                                            href="{{ route('student.show', $student) }}"
                                            wire:navigate
                                        >
                                            مشاهده
                                        </x-ui.button>
                                    @endif
                                    <x-ui.button
                                        type="button"
                                        size="sm"
                                        variant="secondary"
                                        icon="pencil"
                                        href="{{ route('student.edit', $student) }}"
                                        wire:navigate
                                    >
                                        ویرایش
                                    </x-ui.button>

                                    <x-ui.button
                                        type="button"
                                        size="sm"
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $student->id }})"
                                        wire:confirm="این شاگرد حذف شود؟"
                                    >
                                        حذف
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-14 text-center">
                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-900 dark:text-slate-500">
                                        <x-ui.icon name="academic-cap" class="h-6 w-6" />
                                    </span>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-950 dark:text-white">شاگردی ثبت نشده است</h3>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">از دکمه ثبت نام شاگرد، اولین شاگرد را اضافه کنید.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">
                {{ $students->links() }}
            </div>
        @endif
    </section>
</div>
