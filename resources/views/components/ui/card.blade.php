@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'glass' => false,
    'padding' => 'p-5',
])

<section {{ $attributes->merge(['class' => ($glass ? 'ui-glass' : 'ui-surface').' rounded-lg '.$padding]) }}>
    @if ($title || $subtitle || $icon || isset($actions))
        <div class="mb-4 flex items-start justify-between gap-4">
            <div class="flex min-w-0 items-start gap-3">
                @if ($icon)
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <x-ui.icon :name="$icon" class="h-5 w-5" />
                    </span>
                @endif
                <div class="min-w-0">
                    @if ($title)
                        <h3 class="text-sm font-semibold text-slate-950 dark:text-white">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="mt-1 text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            @isset($actions)
                <div class="shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    {{ $slot }}
</section>
