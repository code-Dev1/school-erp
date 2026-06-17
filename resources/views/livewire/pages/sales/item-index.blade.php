<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">فروشات و موجودی</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">اقلام فروش</h2>
        </div>
        <x-ui.button href="{{ route('sales.items.create') }}" icon="plus" wire:navigate>
            ایجاد قلم
        </x-ui.button>
    </section>

    @if (session('status'))
        <x-ui.alert variant="success" dismissible>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            <x-ui.input
                label="جستجو"
                name="search"
                wire:model.live.debounce.400ms="search"
            />

            <x-ui.select
                label="دسته‌بندی"
                name="category"
                :options="$categoryOptions"
                placeholder="همه"
                wire:model.live="category"
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
                        <th class="px-4 py-3 font-semibold text-right">کد کالا</th>
                        <th class="px-4 py-3 font-semibold text-right">نام</th>
                        <th class="px-4 py-3 font-semibold text-right">دسته‌بندی</th>
                        <th class="px-4 py-3 font-semibold text-right">قیمت</th>
                        <th class="px-4 py-3 font-semibold text-right">موجودی</th>
                        <th class="px-4 py-3 font-semibold text-left">عملیات</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($items as $item)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="px-4 py-4 font-medium whitespace-nowrap text-slate-950 dark:text-white">
                                {{ $item->sku }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $item->name }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $categoryOptions[$item->category] ?? $item->category }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $item->unit_price }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $item->stock_quantity }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button
                                        size="sm"
                                        variant="secondary"
                                        href="{{ route('sales.items.show', $item) }}"
                                        wire:navigate
                                    >
                                        نمایش
                                    </x-ui.button>

                                    <x-ui.button
                                        size="sm"
                                        variant="secondary"
                                        href="{{ route('sales.items.edit', $item) }}"
                                        wire:navigate
                                    >
                                        ویرایش
                                    </x-ui.button>

                                    <x-ui.button
                                        type="button"
                                        size="sm"
                                        variant="danger"
                                        icon="trash"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="آیا می‌خواهید این قلم را حذف کنید؟"
                                    >
                                        حذف
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 text-sm text-center py-14 text-slate-500 dark:text-slate-400">
                                هیچ قلم فروشی ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-800">
                {{ $items->links() }}
            </div>
        @endif
    </section>
</div>
