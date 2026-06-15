@props([
    'align' => 'end',
    'width' => 'w-56',
    'label' => 'Open menu',
])

@php
    $alignClasses = match ($align) {
        'start' => 'start-0',
        'center' => 'start-1/2 -translate-x-1/2 rtl:translate-x-1/2',
        default => 'end-0',
    };
@endphp

<div x-data="{ open: false }" x-on:keydown.escape.window="open = false" class="relative inline-block text-start">
    <div x-on:click="open = ! open" aria-haspopup="menu" x-bind:aria-expanded="open">
        @isset($trigger)
            {{ $trigger }}
        @else
            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                {{ $label }}
                <x-ui.icon name="chevron-down" class="h-4 w-4" />
            </button>
        @endisset
    </div>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        x-on:click.outside="open = false"
        class="absolute z-50 mt-2 {{ $width }} {{ $alignClasses }} rounded-lg border border-slate-200 bg-white p-1 shadow-xl shadow-slate-950/10 dark:border-slate-700 dark:bg-slate-950"
        role="menu"
    >
        {{ $slot }}
    </div>
</div>
