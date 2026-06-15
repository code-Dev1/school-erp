@props([
    'title' => 'Revenue',
    'value',
    'change' => null,
    'series' => [34, 48, 42, 64, 58, 76, 69],
])

<x-ui.card :title="$title" icon="currency-dollar" {{ $attributes }}>
    <div class="flex items-end justify-between gap-5">
        <div>
            <p class="text-3xl font-semibold text-slate-950 dark:text-white">{{ $value }}</p>
            @if ($change)
                <p class="mt-2 text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $change }}</p>
            @endif
        </div>
        <div class="flex h-20 items-end gap-1.5">
            @foreach ($series as $point)
                <span class="w-2 rounded-t-full bg-gradient-to-t from-slate-950 to-sky-400 dark:from-sky-500 dark:to-emerald-300" style="height: {{ max(8, min(100, (int) $point)) }}%"></span>
            @endforeach
        </div>
    </div>
</x-ui.card>
