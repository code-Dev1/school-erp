@props([
    'value' => 0,
    'label' => null,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($label)
        <div class="mb-2 flex items-center justify-between text-sm">
            <span class="font-medium text-slate-700 dark:text-slate-200">{{ $label }}</span>
            <span class="text-slate-500 dark:text-slate-400">{{ $value }}%</span>
        </div>
    @endif
    <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
        <div class="h-full rounded-full bg-gradient-to-r from-slate-950 via-indigo-600 to-sky-500 transition-all duration-300 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-300" style="width: {{ max(0, min(100, (int) $value)) }}%"></div>
    </div>
</div>
