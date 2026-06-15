@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'dependsOn' => null,
    'groups' => [],
    'selected' => null,
    'placeholder' => 'Choose an option',
    'emptyLabel' => 'Select the parent field first',
    'hint' => null,
])

@php
    $id = $id ?: ($name ?: 'dependent-select-'.Illuminate\Support\Str::random(8));
@endphp

<div
    x-data="{
        parentValue: '',
        value: @js($selected),
        groups: @js($groups),
        get options() { return this.groups[this.parentValue] || []; },
        sync() { this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }))); },
    }"
    x-init="
        const parent = @js($dependsOn) ? document.querySelector(@js($dependsOn)) : null;
        parentValue = parent ? parent.value : parentValue;
        parent?.addEventListener('change', (event) => { parentValue = event.target.value; value = ''; sync(); });
    "
    class="w-full"
>
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}</label>
    @endif

    <input x-ref="input" type="hidden" name="{{ $name }}" x-model="value" {{ $attributes->whereStartsWith('wire:model') }}>

    <select id="{{ $id }}" x-model="value" x-on:change="sync" class="ui-field">
        <option value="" x-text="options.length ? @js($placeholder) : @js($emptyLabel)"></option>
        <template x-for="option in options" :key="option.value">
            <option :value="option.value" x-text="option.label"></option>
        </template>
    </select>

    @if ($hint)
        <p class="ui-help">{{ $hint }}</p>
    @endif
</div>
