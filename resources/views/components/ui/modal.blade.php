@props([
    'name',
    'title' => null,
    'show' => false,
    'size' => 'lg',
    'fullscreen' => false,
    'closeable' => true,
])

@php
    $sizes = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '4xl' => 'sm:max-w-4xl',
        '6xl' => 'sm:max-w-6xl',
    ];
    $panelSize = $fullscreen ? 'min-h-screen w-screen rounded-none' : (($sizes[$size] ?? $sizes['lg']).' w-full rounded-lg');
@endphp

<div
    x-data="{ open: @js($show) }"
    x-on:open-modal.window="$event.detail === @js($name) && (open = true)"
    x-on:close-modal.window="$event.detail === @js($name) && (open = false)"
    x-on:keydown.escape.window="@js($closeable) && (open = false)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
>
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" @if ($closeable) x-on:click="open = false" @endif></div>

        <div x-show="open" x-transition class="relative {{ $panelSize }} bg-white shadow-2xl dark:bg-slate-950">
            @if ($title || $closeable)
                <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h2>
                    @if ($closeable)
                        <button type="button" x-on:click="open = false" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 ui-focus dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            <x-ui.icon name="x-mark" class="h-5 w-5" />
                            <span class="sr-only">Close</span>
                        </button>
                    @endif
                </div>
            @endif

            <div class="{{ $fullscreen ? 'p-6' : 'p-5' }}">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-5 py-4 dark:border-slate-800">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
