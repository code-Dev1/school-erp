@props([
    'label' => null,
    'name',
    'options' => [],
    'selected' => [],
    'columns' => 'sm:grid-cols-2',
    'hint' => null,
])

@php
    $selected = is_array($selected) ? $selected : [];
@endphp

<fieldset {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($label)
        <legend class="ui-label">{{ $label }}</legend>
    @endif

    <div class="grid gap-3 {{ $columns }}">
        @foreach ($options as $value => $option)
            @php
                $optionValue = is_array($option) ? ($option['value'] ?? $value) : (is_int($value) ? $option : $value);
                $optionLabel = is_array($option) ? ($option['label'] ?? $optionValue) : $option;
                $optionDescription = is_array($option) ? ($option['description'] ?? null) : null;
            @endphp
            <x-ui.checkbox
                :name="$name.'[]'"
                :value="$optionValue"
                :label="$optionLabel"
                :description="$optionDescription"
                :checked="in_array($optionValue, $selected)"
            />
        @endforeach
    </div>

    @if ($hint)
        <p class="ui-help">{{ $hint }}</p>
    @endif
</fieldset>
