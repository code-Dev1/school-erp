@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'success' => ['class' => 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-100', 'icon' => 'check'],
        'error' => ['class' => 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-100', 'icon' => 'x-mark'],
        'warning' => ['class' => 'border-amber-200 bg-amber-50 text-amber-950 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100', 'icon' => 'exclamation-triangle'],
        'info' => ['class' => 'border-sky-200 bg-sky-50 text-sky-950 dark:border-sky-500/30 dark:bg-sky-500/10 dark:text-sky-100', 'icon' => 'information-circle'],
    ];
    $style = $variants[$variant] ?? $variants['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition {{ $attributes->merge(['class' => 'rounded-lg border p-4 '.$style['class']]) }}>
    <div class="flex gap-3">
        <x-ui.icon :name="$style['icon']" class="mt-0.5 h-5 w-5 shrink-0" />
        <div class="min-w-0 flex-1">
            @if ($title)
                <p class="text-sm font-semibold">{{ $title }}</p>
            @endif
            <div class="text-sm leading-6 {{ $title ? 'mt-1' : '' }}">{{ $slot }}</div>
        </div>
        @if ($dismissible)
            <button type="button" x-on:click="show = false" class="rounded-md p-1 opacity-70 transition hover:opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-current">
                <x-ui.icon name="x-mark" class="h-4 w-4" />
                <span class="sr-only">Dismiss</span>
            </button>
        @endif
    </div>
</div>
