@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'icon' => null,
    'iconAfter' => null,
    'pill' => false,
    'full' => false,
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 border font-semibold transition duration-150 ease-out ui-focus disabled:pointer-events-none disabled:opacity-50';
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];
    $variants = [
        'primary' => 'border-transparent bg-slate-950 text-white shadow-sm shadow-slate-950/10 hover:bg-slate-800 active:bg-slate-900 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200',
        'secondary' => 'border-slate-200 bg-white text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 active:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',
        'success' => 'border-transparent bg-emerald-600 text-white shadow-sm shadow-emerald-900/10 hover:bg-emerald-500 active:bg-emerald-700',
        'danger' => 'border-transparent bg-rose-600 text-white shadow-sm shadow-rose-900/10 hover:bg-rose-500 active:bg-rose-700',
        'warning' => 'border-transparent bg-amber-500 text-slate-950 shadow-sm shadow-amber-900/10 hover:bg-amber-400 active:bg-amber-600',
        'ghost' => 'border-transparent bg-transparent text-slate-700 hover:bg-slate-100 active:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-800',
        'outline' => 'border-slate-300 bg-transparent text-slate-800 hover:border-slate-400 hover:bg-slate-50 active:bg-slate-100 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-900',
        'glass' => 'border-white/40 bg-white/70 text-slate-900 shadow-sm backdrop-blur-xl hover:bg-white/90 dark:border-white/10 dark:bg-white/10 dark:text-white dark:hover:bg-white/15',
    ];
    $classes = trim($base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']).' '.($pill ? 'rounded-full' : 'rounded-lg').' '.($full ? 'w-full' : ''));
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $attributes->get('type', 'button') }}" @endif
    @disabled($disabled && ! $href)
    {{ $attributes->except('type')->merge(['class' => $classes]) }}
>
    @if ($icon)
        <x-ui.icon :name="$icon" class="h-4 w-4 shrink-0" />
    @endif

    <span>{{ $slot }}</span>

    @if ($iconAfter)
        <x-ui.icon :name="$iconAfter" class="h-4 w-4 shrink-0" />
    @endif
</{{ $tag }}>
