@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'options' => [],
    'placeholder' => null,
    'hint' => null,
    'error' => null,
    'multiple' => false,
    'disabled' => false,
])

@php
    $id = $id ?: ($name ?: 'select-'.Illuminate\Support\Str::random(8));
    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag;
    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);
    $classes = 'ui-field pe-10 '.($errorText ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500 ' : '');
@endphp

<div {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model'])->except(['class'])->merge(['class' => 'w-full']) }}>
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}</label>
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($multiple) multiple @endif
        @disabled($disabled)
        {{ $attributes->whereStartsWith(['wire:model', 'x-model'])->merge(['class' => $classes]) }}
    >
        @if ($placeholder && ! $multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $option)
            @php
                $optionValue = is_array($option) ? ($option['value'] ?? $value) : (is_int($value) ? $option : $value);
                $optionLabel = is_array($option) ? ($option['label'] ?? $optionValue) : $option;
                $optionDisabled = is_array($option) && ($option['disabled'] ?? false);
            @endphp
            <option value="{{ $optionValue }}" @disabled($optionDisabled)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @if ($hint && ! $errorText)
        <p class="ui-help">{{ $hint }}</p>
    @endif

    @if ($errorText)
        <p class="ui-error">{{ $errorText }}</p>
    @endif
</div>
