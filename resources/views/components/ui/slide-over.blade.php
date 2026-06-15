@props([
    'name',
    'title' => null,
    'side' => 'end',
    'width' => 'max-w-md',
    'show' => false,
])

@php
    $sideClass = $side === 'start' ? 'start-0' : 'end-0';
    $translateClosed = $side === 'start' ? '-translate-x-full rtl:translate-x-full' : 'translate-x-full rtl:-translate-x-full';
@endphp

<div
    x-data="{ open: @js($show) }"
    x-on:open-slide-over.window="$event.detail === @js($name) && (open = true)"
    x-on:close-slide-over.window="$event.detail === @js($name) && (open = false)"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50"
>
    <div x-show="open" x-transition.opacity x-on:click="open = false" class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm"></div>
    <aside
        x-show="open"
        x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="{{ $translateClosed }}"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="{{ $translateClosed }}"
        class="absolute {{ $sideClass }} top-0 h-full w-full {{ $width }} overflow-y-auto bg-white shadow-2xl dark:bg-slate-950"
    >
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <h2 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h2>
            <button type="button" x-on:click="open = false" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 ui-focus dark:hover:bg-slate-800 dark:hover:text-slate-100">
                <x-ui.icon name="x-mark" class="h-5 w-5" />
            </button>
        </div>
        <div class="p-5">{{ $slot }}</div>
    </aside>
</div>
