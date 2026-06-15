@props([
    'type' => 'text',
    'label',
    'name' => null,
    'id' => null,
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'hint' => null,
    'error' => null,
    'disabled' => false,
])

<x-ui.input
    :type="$type"
    :label="$label"
    :name="$name"
    :id="$id"
    :icon="$icon"
    :prefix="$prefix"
    :suffix="$suffix"
    :hint="$hint"
    :error="$error"
    :disabled="$disabled"
    floating
    {{ $attributes }}
/>
