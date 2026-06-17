<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">رسید فروش</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">
                {{ $sale->invoice_number }}
            </h2>
        </div>

        <div class="flex gap-2">
            <x-ui.button
                variant="secondary"
                href="{{ route('sales.edit', $sale) }}"
                wire:navigate
            >
                ویرایش
            </x-ui.button>

            <x-ui.button
                variant="secondary"
                href="{{ route('sales.index') }}"
                wire:navigate
            >
                برگشت
            </x-ui.button>
        </div>
    </section>

    <x-ui.card title="جزئیات رسید" icon="document-chart-bar">
        <dl class="grid gap-4 text-sm md:grid-cols-2 xl:grid-cols-4">

            <div>
                <dt class="text-slate-500">شاگرد</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ $sale->student?->name ?: '-' }}
                </dd>
            </div>

            <div>
                <dt class="text-slate-500">تاریخ</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ \App\Support\School\JalaliDate::format($sale->sold_at) }}
                </dd>
            </div>

            <div>
                <dt class="text-slate-500">مجموع</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ $sale->total_amount }}
                </dd>
            </div>

            <div>
                <dt class="text-slate-500">پرداخت‌شده</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ $sale->paid_amount }}
                </dd>
            </div>

            <div>
                <dt class="text-slate-500">باقی‌مانده</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ $sale->balance_amount }}
                </dd>
            </div>

            <div>
                <dt class="text-slate-500">وضعیت</dt>
                <dd class="font-semibold text-slate-950 dark:text-white">
                    {{ $sale->status }}
                </dd>
            </div>

        </dl>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-right">قلم</th>
                        <th class="px-4 py-3 font-semibold text-right">تعداد</th>
                        <th class="px-4 py-3 font-semibold text-right">قیمت فی واحد</th>
                        <th class="px-4 py-3 font-semibold text-right">مجموع</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-slate-100 dark:divide-slate-800 dark:bg-slate-950">
                    @foreach ($sale->lines as $line)
                        <tr>
                            <td class="px-4 py-4 font-medium whitespace-nowrap text-slate-950 dark:text-white">
                                {{ $line->item?->name }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $line->quantity }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $line->unit_price }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-slate-700 dark:text-slate-200">
                                {{ $line->total_price }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
