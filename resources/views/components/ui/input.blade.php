@props([
    'type' => 'text',
    'label' => null,
    'name' => null,
    'id' => null,
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'hint' => null,
    'error' => null,
    'floating' => false,
    'disabled' => false,
])

@php
    $id = $id ?: ($name ?: 'input-'.Illuminate\Support\Str::random(8));
    $icon = $icon ?: match ($type) {
        'search' => 'magnifying-glass',
        'email' => 'envelope',
        'password' => 'lock-closed',
        'tel' => 'phone',
        'url' => 'link',
        default => null,
    };
    $prefix = $type === 'currency' && ! $prefix ? '$' : $prefix;
    $inputType = $type === 'currency' ? 'text' : $type;
    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag;
    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);
    $hasLeading = $icon || $prefix;
    $hasTrailing = $suffix;
    $inputClasses = 'ui-field '.($hasLeading ? 'ps-10 ' : '').($hasTrailing ? 'pe-12 ' : '').($errorText ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500 ' : '').($floating ? 'peer pt-5 pb-2 placeholder:text-transparent ' : '');
@endphp

<div {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model'])->except(['class', 'placeholder'])->merge(['class' => 'w-full']) }}>
    @if ($label && ! $floating)
        <label for="{{ $id }}" class="ui-label">{{ $label }}</label>
    @endif

    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-slate-400 dark:text-slate-500">
                <x-ui.icon :name="$icon" class="h-4 w-4" />
            </span>
        @elseif ($prefix)
            <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ $prefix }}
            </span>
        @endif

        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $inputType }}"
            placeholder="{{ $floating ? ' ' : $attributes->get('placeholder') }}"
            @disabled($disabled)
            {{ $attributes->whereStartsWith(['wire:model', 'x-model'])->merge(['class' => $inputClasses]) }}
        >

        @if ($floating && $label)
            <label
                for="{{ $id }}"
                class="pointer-events-none absolute start-3 top-2 text-xs font-medium text-slate-500 transition-all peer-placeholder-shown:top-2.5 peer-placeholder-shown:text-sm peer-focus:top-2 peer-focus:text-xs peer-focus:text-indigo-600 dark:text-slate-400 dark:peer-focus:text-sky-400 {{ $hasLeading ? 'start-10' : '' }}"
            >
                {{ $label }}
            </label>
        @endif

        @if ($suffix)
            <span class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-3 text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ $suffix }}
            </span>
        @endif
    </div>

    @if ($hint && ! $errorText)
        <p class="ui-help">{{ $hint }}</p>
    @endif

    @if ($errorText)
        <p class="ui-error">{{ $errorText }}</p>
    @endif
</div>
