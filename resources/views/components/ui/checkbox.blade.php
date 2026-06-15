@props([
    'label' => null,
    'description' => null,
    'name' => null,
    'id' => null,
    'value' => '1',
    'checked' => false,
    'disabled' => false,
])

@php
    $id = $id ?: ($name ? $name.'-'.Illuminate\Support\Str::random(5) : 'checkbox-'.Illuminate\Support\Str::random(8));
@endphp

<label for="{{ $id }}" {{ $attributes->whereDoesntStartWith('wire:model')->except(['class'])->merge(['class' => 'flex cursor-pointer items-start gap-3']) }}>
    <span class="relative mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            type="checkbox"
            class="peer h-5 w-5 rounded-md border-slate-300 text-slate-950 transition focus:ring-2 focus:ring-indigo-500/30 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400"
            @checked($checked)
            @disabled($disabled)
            {{ $attributes->whereStartsWith('wire:model') }}
        >
    </span>

    @if ($label || $description)
        <span>
            @if ($label)
                <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">{{ $label }}</span>
            @endif
            @if ($description)
                <span class="mt-0.5 block text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $description }}</span>
            @endif
        </span>
    @else
        <span class="sr-only">{{ $slot }}</span>
    @endif
</label>
