@props([
    'icon' => 'ellipsis-vertical',
    'label' => 'Action',
    'variant' => 'ghost',
    'size' => 'md',
    'href' => null,
])

@php
    $sizes = [
        'sm' => 'h-8 w-8',
        'md' => 'h-10 w-10',
        'lg' => 'h-12 w-12',
    ];
    $variants = [
        'primary' => 'border-transparent bg-slate-950 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200',
        'secondary' => 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',
        'danger' => 'border-transparent bg-rose-600 text-white hover:bg-rose-500',
        'ghost' => 'border-transparent bg-transparent text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white',
        'outline' => 'border-slate-300 bg-transparent text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900',
        'glass' => 'border-white/40 bg-white/70 text-slate-900 backdrop-blur-xl hover:bg-white/90 dark:border-white/10 dark:bg-white/10 dark:text-white dark:hover:bg-white/15',
    ];
    $classes = 'group relative inline-flex shrink-0 items-center justify-center rounded-lg border transition duration-150 ui-focus disabled:pointer-events-none disabled:opacity-50 '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['ghost']);
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    aria-label="{{ $label }}"
    title="{{ $label }}"
    @if ($href) href="{{ $href }}" @else type="{{ $attributes->get('type', 'button') }}" @endif
    {{ $attributes->except('type')->merge(['class' => $classes]) }}
>
    <x-ui.icon :name="$icon" class="h-5 w-5" />
    <span class="sr-only">{{ $label }}</span>
</{{ $tag }}>
