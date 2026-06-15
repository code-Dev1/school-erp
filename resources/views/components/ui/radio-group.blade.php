@props([
    'label' => null,
    'name',
    'options' => [],
    'selected' => null,
    'columns' => 'sm:grid-cols-2',
    'hint' => null,
])

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
                $id = $name.'-'.$loop->index;
            @endphp
            <label for="{{ $id }}" class="flex cursor-pointer gap-3 rounded-lg border border-slate-200 bg-white p-3 text-sm shadow-sm transition hover:border-slate-300 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-slate-700">
                <input id="{{ $id }}" name="{{ $name }}" value="{{ $optionValue }}" type="radio" class="mt-0.5 border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400" @checked((string) $selected === (string) $optionValue)>
                <span>
                    <span class="block font-medium text-slate-800 dark:text-slate-100">{{ $optionLabel }}</span>
                    @if ($optionDescription)
                        <span class="mt-0.5 block text-slate-500 dark:text-slate-400">{{ $optionDescription }}</span>
                    @endif
                </span>
            </label>
        @endforeach
    </div>

    @if ($hint)
        <p class="ui-help">{{ $hint }}</p>
    @endif
</fieldset>
