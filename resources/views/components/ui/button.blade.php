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
    $base = 'inline-flex items-center justify-center gap-2 border font-semibold tracking-wide transition-all duration-200 ease-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 disabled:pointer-events-none disabled:opacity-50';

    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];

    $variants = [
        'primary' => 'border-transparent bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/25 hover:from-blue-500 hover:to-indigo-500 hover:shadow-xl hover:shadow-blue-600/30 active:from-blue-700 active:to-indigo-700 focus:ring-blue-500/25',

        'secondary' => 'border-slate-200 bg-white text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-50 active:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',

        'success' => 'border-transparent bg-emerald-600 text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-500 hover:shadow-lg active:bg-emerald-700 focus:ring-emerald-500/25',

        'danger' => 'border-transparent bg-rose-600 text-white shadow-md shadow-rose-600/20 hover:bg-rose-500 hover:shadow-lg active:bg-rose-700 focus:ring-rose-500/25',

        'warning' => 'border-transparent bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20 hover:bg-amber-400 hover:shadow-lg active:bg-amber-600 focus:ring-amber-500/25',

        'ghost' => 'border-transparent bg-transparent text-slate-700 hover:bg-slate-100 active:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-800',

        'outline' => 'border-slate-300 bg-transparent text-slate-800 hover:border-blue-400 hover:bg-blue-50 hover:text-blue-700 active:bg-blue-100 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-900',

        'glass' => 'border-white/40 bg-white/70 text-slate-900 shadow-sm backdrop-blur-xl hover:bg-white/90 dark:border-white/10 dark:bg-white/10 dark:text-white dark:hover:bg-white/15',
    ];

    $classes = trim(
        $base . ' ' .
        ($sizes[$size] ?? $sizes['md']) . ' ' .
        ($variants[$variant] ?? $variants['primary']) . ' ' .
        ($pill ? 'rounded-full' : 'rounded-xl') . ' ' .
        ($full ? 'w-full' : '')
    );

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href)
        href="{{ $href }}"
    @else
        type="{{ $attributes->get('type', 'button') }}"
    @endif
    @disabled($disabled && ! $href)
    {{ $attributes->except('type')->merge(['class' => $classes]) }}
>
    @if ($icon)
        <x-ui.icon :name="$icon" class="w-4 h-4 shrink-0" />
    @endif

    <span>{{ $slot }}</span>

    @if ($iconAfter)
        <x-ui.icon :name="$iconAfter" class="w-4 h-4 shrink-0" />
    @endif
</{{ $tag }}>
