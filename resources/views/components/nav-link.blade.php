@props([
    'active' => false,
    'href' => '#',
    'icon' => null,
    'badge' => null,
    'collapsed' => true,
    'navigate' => true,
])

@php
    $classes = ($active ?? false)
        ? 'bg-slate-950 text-white shadow-lg shadow-slate-950/15 ring-1 ring-slate-900/10 dark:bg-white dark:text-slate-950 dark:shadow-black/25'
        : 'text-slate-600 hover:bg-white/85 hover:text-slate-950 hover:shadow-sm dark:text-slate-300 dark:hover:bg-slate-900/85 dark:hover:text-white';

    $href = $attributes->get('href', $href);
@endphp

<a
    href="{{ $href }}"
    @if ($navigate && $href !== '#') wire:navigate @endif
    {{ $attributes->except('href')->merge(['class' => 'group flex min-h-11 items-center gap-3 rounded-2xl px-3 py-2.5 text-sm font-semibold transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 '.$classes]) }}
>
    @if ($icon)
        <x-ui.icon :name="$icon" class="h-5 w-5 shrink-0 opacity-90" />
    @endif

    <span @class(['min-w-0 flex-1 truncate', 'admin-collapse-hide' => $collapsed])>
        {{ $slot }}
    </span>

    @if ($badge)
        <span @class(['rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 group-hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:group-hover:bg-slate-700', 'admin-collapse-hide' => $collapsed])>
            {{ $badge }}
        </span>
    @endif
</a>
