@props([
    'tabs' => [],
    'active' => null,
    'vertical' => false,
])

@php
    $active = $active ?: array_key_first($tabs);
@endphp

<div x-data="{ active: @js($active) }" {{ $attributes->merge(['class' => $vertical ? 'grid gap-4 md:grid-cols-[14rem_1fr]' : 'space-y-4']) }}>
    <div @class([
        'flex gap-1 rounded-lg bg-slate-100 p-1 dark:bg-slate-900',
        'md:flex-col' => $vertical,
        'overflow-x-auto' => ! $vertical,
    ]) role="tablist">
        @foreach ($tabs as $key => $tab)
            <button
                type="button"
                x-on:click="active = @js($key)"
                x-bind:aria-selected="active === @js($key)"
                @class([
                    'rounded-md px-3 py-2 text-sm font-medium transition ui-focus',
                    'text-start' => $vertical,
                ])
                x-bind:class="active === @js($key) ? 'bg-white text-slate-950 shadow-sm dark:bg-slate-800 dark:text-white' : 'text-slate-600 hover:text-slate-950 dark:text-slate-400 dark:hover:text-white'"
                role="tab"
            >
                {{ is_array($tab) ? ($tab['label'] ?? $key) : $tab }}
            </button>
        @endforeach
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-950">
        @php $hasInlineContent = collect($tabs)->contains(fn ($tab) => is_array($tab) && array_key_exists('content', $tab)); @endphp

        @if ($hasInlineContent)
            @foreach ($tabs as $key => $tab)
                <div x-show="active === @js($key)" x-cloak>
                    {!! is_array($tab) ? ($tab['content'] ?? '') : '' !!}
                </div>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </div>
</div>
