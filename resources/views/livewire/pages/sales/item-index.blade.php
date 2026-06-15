<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sales and inventory</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">Sale items</h2>
        </div>
        <x-ui.button href="{{ route('sales.items.create') }}" icon="plus" wire:navigate>Create item</x-ui.button>
    </section>

    @if (session('status')) <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert> @endif

    <x-ui.card>
        <div class="grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            <x-ui.input label="Search" name="search" wire:model.live.debounce.400ms="search" />
            <x-ui.select label="Category" name="category" :options="$categoryOptions" placeholder="All" wire:model.live="category" />
            <x-ui.button type="button" variant="secondary" wire:click="clearFilters">Clear</x-ui.button>
        </div>
    </x-ui.card>

    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold">SKU</th>
                        <th class="px-4 py-3 text-right font-semibold">Name</th>
                        <th class="px-4 py-3 text-right font-semibold">Category</th>
                        <th class="px-4 py-3 text-right font-semibold">Price</th>
                        <th class="px-4 py-3 text-right font-semibold">Stock</th>
                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($items as $item)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $item->sku }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $item->name }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $categoryOptions[$item->category] ?? $item->category }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $item->unit_price }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $item->stock_quantity }}</td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-ui.button size="sm" variant="secondary" href="{{ route('sales.items.show', $item) }}" wire:navigate>Show</x-ui.button>
                                    <x-ui.button size="sm" variant="secondary" href="{{ route('sales.items.edit', $item) }}" wire:navigate>Edit</x-ui.button>
                                    <x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $item->id }})" wire:confirm="Delete this item?">Delete</x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">No sale items registered.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages()) <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $items->links() }}</div> @endif
    </section>
</div>
