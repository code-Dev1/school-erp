<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">گزارش ها</p>
            <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>
        <x-ui.button href="{{ route('reports.create') }}" icon="plus" wire:navigate>ثبت گزارش</x-ui.button>
    </section>

    <div class="grid gap-4 md:grid-cols-3">
        @foreach ($cards as $card)
            <x-ui.card>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ $card['value'] }}</p>
            </x-ui.card>
        @endforeach
    </div>
</div>
