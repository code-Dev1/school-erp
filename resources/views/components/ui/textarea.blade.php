@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'rows' => 4,
    'hint' => null,
    'error' => null,
    'autoResize' => false,
    'disabled' => false,
])

@php
    $id = $id ?: ($name ?: 'textarea-'.Illuminate\Support\Str::random(8));
    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag;
    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);
    $classes = 'ui-field min-h-[7rem] resize-y leading-6 '.($errorText ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500 ' : '').($autoResize ? 'overflow-hidden resize-none ' : '');
@endphp

<div {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model'])->except(['class'])->merge(['class' => 'w-full']) }}>
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @disabled($disabled)
        @if ($autoResize)
            x-data
            x-init="$nextTick(() => { $el.style.height = 'auto'; $el.style.height = `${$el.scrollHeight}px`; })"
            x-on:input="$el.style.height = 'auto'; $el.style.height = `${$el.scrollHeight}px`"
        @endif
        {{ $attributes->whereStartsWith(['wire:model', 'x-model'])->merge(['class' => $classes]) }}
    >{{ $slot }}</textarea>

    @if ($hint && ! $errorText)
        <p class="ui-help">{{ $hint }}</p>
    @endif

    @if ($errorText)
        <p class="ui-error">{{ $errorText }}</p>
    @endif
</div>
