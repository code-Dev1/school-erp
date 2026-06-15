@props([
    'label',
    'value',
    'caption' => null,
    'icon' => 'chart-bar',
])

<x-ui.card {{ $attributes }}>
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $value }}</p>
        </div>
        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
            <x-ui.icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
    @if (trim($slot))
        <div class="mt-5">{{ $slot }}</div>
    @endif
    @if ($caption)
        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $caption }}</p>
    @endif
</x-ui.card>
