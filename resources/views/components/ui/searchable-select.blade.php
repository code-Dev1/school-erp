@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Search options',
    'hint' => null,
    'error' => null,
])

@php
    $id = $id ?: ($name ?: 'searchable-select-'.Illuminate\Support\Str::random(8));
    $items = collect($options)->map(function ($option, $value) {
        return is_array($option)
            ? ['value' => $option['value'] ?? $value, 'label' => $option['label'] ?? ($option['value'] ?? $value)]
            : ['value' => is_int($value) ? $option : $value, 'label' => $option];
    })->values();
    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag;
    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);
@endphp

<div
    x-data="uiCombobox({ options: @js($items), selected: @js($selected) })"
    x-init="query = selected ? labelFor(selected) : ''"
    class="relative w-full"
>
    @if ($label)
        <label for="{{ $id }}-search" class="ui-label">{{ $label }}</label>
    @endif

    <input
        x-ref="input"
        type="hidden"
        name="{{ $name }}"
        x-model="selected"
        {{ $attributes->whereStartsWith('wire:model') }}
    >

    <div class="relative">
        <x-ui.icon name="magnifying-glass" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
        <input
            id="{{ $id }}-search"
            type="text"
            x-model="query"
            x-on:focus="open = true"
            x-on:input="open = true"
            x-on:keydown.escape="open = false"
            x-on:keydown.enter.prevent="filtered[activeIndex] && choose(filtered[activeIndex])"
            placeholder="{{ $placeholder }}"
            class="ui-field ps-10"
        >
    </div>

    <div
        x-cloak
        x-show="open"
        x-on:click.outside="open = false"
        class="absolute z-50 mt-2 max-h-64 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-1 shadow-xl shadow-slate-950/10 dark:border-slate-700 dark:bg-slate-950"
    >
        <template x-for="(option, index) in filtered" :key="option.value">
            <button
                type="button"
                x-on:mouseenter="activeIndex = index"
                x-on:click="choose(option)"
                class="flex w-full items-center justify-between rounded-md px-3 py-2 text-start text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
            >
                <span x-text="option.label"></span>
                <x-ui.icon name="check" class="h-4 w-4 text-emerald-500" x-show="isSelected(option.value)" />
            </button>
        </template>

        <p x-show="filtered.length === 0" class="px-3 py-2 text-sm text-slate-500">No results found.</p>
    </div>

    @if ($hint && ! $errorText)
        <p class="ui-help">{{ $hint }}</p>
    @endif

    @if ($errorText)
        <p class="ui-error">{{ $errorText }}</p>
    @endif
</div>
