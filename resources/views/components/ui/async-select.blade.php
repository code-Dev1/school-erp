@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'endpoint',
    'selected' => null,
    'placeholder' => 'Search remote data',
    'minChars' => 2,
    'hint' => null,
])

@php
    $id = $id ?: ($name ?: 'async-select-'.Illuminate\Support\Str::random(8));
@endphp

<div x-data="uiAsyncSelect({ endpoint: @js($endpoint), minChars: @js($minChars), selected: @js($selected) })" class="relative w-full">
    @if ($label)
        <label for="{{ $id }}-search" class="ui-label">{{ $label }}</label>
    @endif

    <input x-ref="input" type="hidden" name="{{ $name }}" x-model="selected" {{ $attributes->whereStartsWith('wire:model') }}>

    <div class="relative">
        <x-ui.icon name="magnifying-glass" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
        <input id="{{ $id }}-search" type="text" x-model.debounce.350ms="query" x-on:input.debounce.350ms="search" x-on:focus="open = true" placeholder="{{ $placeholder }}" class="ui-field ps-10 pe-10">
        <x-ui.icon name="arrow-path" class="absolute end-3 top-1/2 h-4 w-4 -translate-y-1/2 animate-spin text-slate-400" x-show="loading" />
    </div>

    <div x-cloak x-show="open && options.length" x-on:click.outside="open = false" class="absolute z-50 mt-2 max-h-64 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-950">
        <template x-for="option in options" :key="option.value">
            <button type="button" x-on:click="choose(option)" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-start text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                <span x-text="option.label"></span>
            </button>
        </template>
    </div>

    @if ($hint)
        <p class="ui-help">{{ $hint }}</p>
    @endif
</div>
