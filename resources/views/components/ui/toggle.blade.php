@props([
    'label' => null,
    'description' => null,
    'name' => null,
    'id' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $id = $id ?: ($name ?: 'toggle-'.Illuminate\Support\Str::random(8));
@endphp

<label for="{{ $id }}" {{ $attributes->whereDoesntStartWith('wire:model')->except(['class'])->merge(['class' => 'flex cursor-pointer items-center justify-between gap-4']) }}>
    <span>
        @if ($label)
            <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">{{ $label }}</span>
        @endif
        @if ($description)
            <span class="mt-0.5 block text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $description }}</span>
        @endif
    </span>

    <span class="relative inline-flex h-6 w-11 shrink-0 items-center">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="checkbox"
            value="1"
            class="peer sr-only"
            @checked($checked)
            @disabled($disabled)
            {{ $attributes->whereStartsWith('wire:model') }}
        >
        <span class="absolute inset-0 rounded-full bg-slate-200 transition peer-checked:bg-slate-950 peer-focus-visible:ring-2 peer-focus-visible:ring-indigo-500 peer-focus-visible:ring-offset-2 dark:bg-slate-700 dark:peer-checked:bg-sky-500"></span>
        <span class="relative ms-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition peer-checked:translate-x-5 rtl:peer-checked:-translate-x-5"></span>
    </span>
</label>
