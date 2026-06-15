@props([
    'icon' => 'plus',
    'label' => 'Create',
    'position' => 'bottom-6 end-6',
    'variant' => 'primary',
    'href' => null,
])

<x-ui.icon-button
    :href="$href"
    :icon="$icon"
    :label="$label"
    :variant="$variant"
    size="lg"
    {{ $attributes->merge(['class' => 'fixed z-40 '.$position.' rounded-full shadow-2xl shadow-slate-950/20']) }}
/>
