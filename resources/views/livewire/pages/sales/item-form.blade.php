<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sales and inventory</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('sales.items.index') }}" icon="chevron-right" wire:navigate>Back</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="Please check the form.">Some fields need correction.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="Item information" icon="rectangle-stack">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="SKU" name="form.sku" wire:model="form.sku" />
                <x-ui.input label="Name" name="form.name" wire:model="form.name" />
                <x-ui.select label="Category" name="form.category" :options="$categoryOptions" wire:model="form.category" />
                <x-ui.input type="number" label="Unit price" name="form.unit_price" min="0" step="0.01" wire:model="form.unit_price" />
                <x-ui.input type="number" label="Stock quantity" name="form.stock_quantity" min="0" wire:model="form.stock_quantity" />
                <x-ui.input type="number" label="Reorder level" name="form.reorder_level" min="0" wire:model="form.reorder_level" />
                <x-ui.select label="Status" name="form.status" :options="$statusOptions" wire:model="form.status" />
                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea label="Description" name="form.description" rows="3" wire:model="form.description" />
                </div>
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="secondary" href="{{ route('sales.items.index') }}" wire:navigate>Cancel</x-ui.button>
            <x-ui.button type="submit" icon="check">Save</x-ui.button>
        </div>
    </form>
</div>
