<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">فروشات و موجودی</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">فروشات شاگردان</h2>
        </div>

        <x-ui.button href="{{ route('sales.create') }}" icon="plus" wire:navigate>
            ایجاد فروش
        </x-ui.button>
    </section>

    @if (session('status'))
        <x-ui.alert variant="success" dismissible>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[220px_auto] md:items-end">
            <x-ui.select
                label="وضعیت"
                name="status"
                :options="$statusOptions"
                placeholder="همه"
                wire:model.live="status"
            />

            <x-ui.button
                type="button"
                variant="secondary"
                wire:click="clearFilters"
            >
                پاک‌کردن
            </x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-right">نمبر فاکتور</th>
                        <th class="px-4 py-3 font-semibold text-right">شاگرد</th>
                        <th class="px-4 py-3 font-semibold text-right">تاریخ</th>
                        <th class="px-4 py-3 font-semibold text-right">مجموع</th>
                        <th class="px-4 py-3 font-semibold text-right">پرداخت‌شده</th>
                        <th class="px-4 py-3 font-semibold text-right">وضعیت</th>
                        <th class="px-4 py-3 font-semibold text-left">عملیات</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($sales as $sale)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="px-4 py-4 font-medium whitespace-nowrap text-slate-950 dark:text-white">
                                {{ $sale->invoice_number }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $sale->student?->name ?: '-' }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ \App\Support\School\JalaliDate::format($sale->sold_at) }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $sale->total_amount }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $sale->paid_amount }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap">
                                <x-ui.badge>
                                    {{ $statusOptions[$sale->status] ?? $sale->status }}
                                </x-ui.badge>
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        size="sm"
                                        variant="secondary"
                                        href="{{ route('sales.show', $sale) }}"
                                        wire:navigate
                                    >
                                        نمایش
                                    </x-ui.button>

                                    <x-ui.button
                                        size="sm"
                                        variant="secondary"
                                        href="{{ route('sales.edit', $sale) }}"
                                        wire:navigate
                                    >
                                        ویرایش
                                    </x-ui.button>

                                    <x-ui.button
                                        type="button"
                                        size="sm"
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $sale->id }})"
                                        wire:confirm="آیا می‌خواهید این فروش را حذف کنید؟"
                                    >
                                        حذف
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 text-sm text-center py-14 text-slate-500 dark:text-slate-400">
                                هیچ فروشی ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sales->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-800">
                {{ $sales->links() }}
            </div>
        @endif
    </section>
</div>
