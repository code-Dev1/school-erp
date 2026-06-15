<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sales and inventory</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">Edit sale {{ $sale->invoice_number }}</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('sales.index') }}" icon="chevron-right" wire:navigate>Back</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="Please check the form.">Some fields need correction.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="Payment information" icon="banknotes">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.select label="Student" name="form.student_id" :options="$studentOptions" placeholder="Optional" wire:model="form.student_id" />
                <x-ui.input type="date" label="Sale date" name="form.sold_at" wire:model="form.sold_at" />
                <x-ui.input type="number" label="Discount" name="form.discount_amount" min="0" step="0.01" wire:model="form.discount_amount" />
                <x-ui.input type="number" label="Paid amount" name="form.paid_amount" min="0" step="0.01" wire:model="form.paid_amount" />
                <x-ui.select label="Status" name="form.status" :options="$statusOptions" wire:model="form.status" />
                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea label="Note" name="form.note" rows="3" wire:model="form.note" />
                </div>
            </div>
        </x-ui.card>
        <div class="flex justify-end gap-3">
            <x-ui.button variant="secondary" href="{{ route('sales.index') }}" wire:navigate>Cancel</x-ui.button>
            <x-ui.button type="submit" icon="check">Save</x-ui.button>
        </div>
    </form>
</div>
