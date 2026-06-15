<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sales and inventory</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $item->name }}</h2>
        </div>
        <div class="flex gap-2">
            <x-ui.button variant="secondary" href="{{ route('sales.items.edit', $item) }}" wire:navigate>Edit</x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('sales.items.index') }}" wire:navigate>Back</x-ui.button>
        </div>
    </section>

    <x-ui.card title="Item details" icon="rectangle-stack">
        <dl class="grid gap-4 text-sm md:grid-cols-2 xl:grid-cols-4">
            <div><dt class="text-slate-500">SKU</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->sku }}</dd></div>
            <div><dt class="text-slate-500">Category</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->category }}</dd></div>
            <div><dt class="text-slate-500">Unit price</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->unit_price }}</dd></div>
            <div><dt class="text-slate-500">Stock</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->stock_quantity }}</dd></div>
            <div><dt class="text-slate-500">Reorder level</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->reorder_level }}</dd></div>
            <div><dt class="text-slate-500">Status</dt><dd class="font-semibold text-slate-950 dark:text-white">{{ $item->status }}</dd></div>
        </dl>
    </x-ui.card>
</div>
