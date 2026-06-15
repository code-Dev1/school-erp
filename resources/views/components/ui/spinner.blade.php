@props([
    'label' => 'Loading',
])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-300']) }}>
    <x-ui.icon name="arrow-path" class="h-5 w-5 animate-spin" />
    <span>{{ $label }}</span>
</span>
