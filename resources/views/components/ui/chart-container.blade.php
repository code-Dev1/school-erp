@props([
    'title' => 'Chart',
    'subtitle' => null,
])

<x-ui.card :title="$title" :subtitle="$subtitle" icon="chart-bar" {{ $attributes }}>
    <div class="min-h-64 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
        @if (trim($slot))
            {{ $slot }}
        @else
            <div class="flex h-56 items-end justify-center gap-3">
                @foreach ([42, 64, 58, 78, 52, 86, 70, 92] as $point)
                    <span class="w-7 rounded-t-lg bg-gradient-to-t from-slate-950 to-sky-400 dark:from-sky-500 dark:to-emerald-300" style="height: {{ $point }}%"></span>
                @endforeach
            </div>
        @endif
    </div>
</x-ui.card>
