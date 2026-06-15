<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sale receipt</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $sale->invoice_number }}</h2>
        </div>
        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('sales.edit', $sale) }}" wire:navigate>Edit</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('sales.index') }}" wire:navigate>Back</x-ui.button>
        </div>
    </section>

    <x-ui.card title="Receipt" icon="document-chart-bar">
        <dl class="grid gap-4 text-sm md:grid-cols-2 xl:grid-cols-4">
            <div><dt class="text-slate-500">Student</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $sale->student?->name ?: '-' }}</dd></div>
            <div><dt class="text-slate-500">Date</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ \App\Support\School\JalaliDate::format($sale->sold_at) }}</dd></div>
            <div><dt class="text-slate-500">Total</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $sale->total_amount }}</dd></div>
            <div><dt class="text-slate-500">Paid</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $sale->paid_amount }}</dd></div>
            <div><dt class="text-slate-500">Balance</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $sale->balance_amount }}</dd></div>
            <div><dt class="text-slate-500">Status</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $sale->status }}</dd></div>
        </dl>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">Item</th>
                        <th class="px-4 py-3 text-right font-semibold">Quantity</th>
                        <th class="px-4 py-3 text-right font-semibold">Unit price</th>
                        <th class="px-4 py-3 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @foreach ($sale->lines as $line)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $line->item?->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $line->quantity }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $line->unit_price }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $line->total_price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
