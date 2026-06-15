@props([
    'title' => 'Recent activity',
    'items' => [],
])

<x-ui.card :title="$title" icon="clock" {{ $attributes }}>
    <div class="space-y-4">
        @forelse ($items as $item)
            <div class="flex gap-3">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                    <x-ui.icon :name="$item['icon'] ?? 'check'" class="h-4 w-4" />
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $item['title'] ?? '' }}</p>
                    @if ($item['description'] ?? null)
                        <p class="mt-0.5 text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $item['description'] }}</p>
                    @endif
                    @if ($item['time'] ?? null)
                        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">{{ $item['time'] }}</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500 dark:text-slate-400">No activity yet.</p>
        @endforelse
    </div>
</x-ui.card>
