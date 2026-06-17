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
    $id = $id ?: ($name ?: 'select-' . Illuminate\Support\Str::random(8));

    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag();

    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);

    $classes =
        'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-sm text-slate-700 shadow-sm transition-all duration-200 appearance-none
        focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 focus:outline-none
        hover:border-slate-400
        dark:border-slate-700 dark:bg-slate-900 dark:text-white
        dark:hover:border-slate-600
        disabled:cursor-not-allowed disabled:opacity-60 ' .
        ($errorText ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500' : '');
@endphp

<div
    {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model'])->except(['class'])->merge(['class' => 'w-full space-y-2']) }}>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <select id="{{ $id }}" name="{{ $name }}" @if ($multiple) multiple @endif
            @disabled($disabled)
            {{ $attributes->whereStartsWith(['wire:model', 'x-model'])->merge(['class' => $classes]) }}>
            @if ($placeholder && !$multiple)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach ($options as $value => $option)
                @php
                    $optionValue = is_array($option) ? $option['id'] : $value;

                    $optionLabel = is_array($option) ? $option['label'] ?? ($option['name'] ?? $optionValue) : $option;

                    $optionDisabled = is_array($option) && ($option['disabled'] ?? false);
                @endphp

                <option value="{{ $optionValue }}" @disabled($optionDisabled)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        @unless ($multiple)
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                </svg>
            </div>
        @endunless
    </div>

    @if ($hint && !$errorText)
        <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ $hint }}
        </p>
    @endif

    @if ($errorText)
        <p class="text-xs font-medium text-rose-600">
            {{ $errorText }}
        </p>
    @endif
</div>
