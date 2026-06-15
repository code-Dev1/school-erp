@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'options' => [],
    'selected' => [],
    'placeholder' => 'Type a tag and press Enter',
    'hint' => 'Press Enter to create a new tag.',
])

@php
    $id = $id ?: ($name ?: 'tag-select-'.Illuminate\Support\Str::random(8));
    $items = collect($options)->map(fn ($option) => is_array($option) ? $option : ['value' => $option, 'label' => $option])->values();
    $selected = is_array($selected) ? $selected : [];
@endphp

<div x-data="uiCombobox({ options: @js($items), selected: @js($selected), multiple: true, allowTags: true })" class="relative w-full">
    @if ($label)
        <label for="{{ $id }}-search" class="ui-label">{{ $label }}</label>
    @endif

    <input x-ref="input" type="hidden" name="{{ $name }}" x-bind:value="JSON.stringify(selected)" {{ $attributes->whereStartsWith('wire:model') }}>

    <div class="flex min-h-[2.75rem] flex-wrap items-center gap-2 rounded-lg border border-slate-300 bg-white px-2 py-1.5 shadow-sm focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950">
        <template x-for="value in selected" :key="value">
            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-sky-400/10 dark:text-sky-200">
                <span x-text="labelFor(value)"></span>
                <button type="button" x-on:click="remove(value)" class="rounded-full text-indigo-400 hover:text-rose-500">
                    <x-ui.icon name="x-mark" class="h-3.5 w-3.5" />
                </button>
            </span>
        </template>

        <input id="{{ $id }}-search" type="text" x-model="query" x-on:focus="open = true" x-on:input="open = true" x-on:keydown.enter.prevent="filtered.length ? choose(filtered[activeIndex]) : addTag()" placeholder="{{ $placeholder }}" class="min-w-[12rem] flex-1 border-0 bg-transparent p-1 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 dark:text-slate-100">
    </div>

    <div x-cloak x-show="open && filtered.length" x-on:click.outside="open = false" class="absolute z-50 mt-2 max-h-64 w-full overflow-auto rounded-lg border border-slate-200 bg-white p-1 shadow-xl dark:border-slate-700 dark:bg-slate-950">
        <template x-for="(option, index) in filtered" :key="option.value">
            <button type="button" x-on:mouseenter="activeIndex = index" x-on:click="choose(option)" class="flex w-full items-center justify-between rounded-md px-3 py-2 text-start text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                <span x-text="option.label"></span>
                <x-ui.icon name="check" class="h-4 w-4 text-emerald-500" x-show="isSelected(option.value)" />
            </button>
        </template>
    </div>

    @if ($hint)
        <p class="ui-help">{{ $hint }}</p>
    @endif
</div>
