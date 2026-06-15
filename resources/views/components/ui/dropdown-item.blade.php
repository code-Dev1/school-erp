@props([
    'href' => null,
    'icon' => null,
    'danger' => false,
    'active' => false,
])

@php
    $classes = 'group relative flex w-full items-center gap-2 rounded-md px-3 py-2 text-start text-sm transition ';
    $classes .= $danger
        ? 'text-rose-700 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10 '
        : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800 ';
    $classes .= $active ? 'bg-slate-100 dark:bg-slate-800 ' : '';
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="button" @endif
    role="menuitem"
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if ($icon)
        <x-ui.icon :name="$icon" class="h-4 w-4 shrink-0" />
    @endif
    <span class="min-w-0 flex-1 truncate">{{ $slot }}</span>
    @isset($submenu)
        <x-ui.icon name="chevron-right" class="h-4 w-4" />
        <div class="absolute start-full top-0 ms-1 hidden min-w-48 rounded-lg border border-slate-200 bg-white p-1 shadow-xl group-hover:block dark:border-slate-700 dark:bg-slate-950">
            {{ $submenu }}
        </div>
    @endisset
</{{ $tag }}>
