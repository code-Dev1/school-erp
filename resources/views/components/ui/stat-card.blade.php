@props([
    'label',
    'value',
    'change' => null,
    'trend' => 'neutral',
    'icon' => 'chart-bar',
    'accent' => 'indigo',
])

@php
    $accents = [
        'indigo' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-300',
        'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300',
        'amber' => 'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300',
        'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-400/10 dark:text-rose-300',
        'sky' => 'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-300',
    ];
    $trendClass = match ($trend) {
        'up' => 'text-emerald-600 dark:text-emerald-400',
        'down' => 'text-rose-600 dark:text-rose-400',
        default => 'text-slate-500 dark:text-slate-400',
    };
@endphp

<x-ui.card {{ $attributes }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-3xl font-semibold tracking-normal text-slate-950 dark:text-white">{{ $value }}</p>
            @if ($change)
                <p class="mt-2 text-sm font-medium {{ $trendClass }}">{{ $change }}</p>
            @endif
        </div>
        <span class="flex h-11 w-11 items-center justify-center rounded-lg {{ $accents[$accent] ?? $accents['indigo'] }}">
            <x-ui.icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</x-ui.card>
