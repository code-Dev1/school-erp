@props([
    'tabs' => [],
    'active' => null,
])

<x-ui.tabs :tabs="$tabs" :active="$active" vertical {{ $attributes }}>
    {{ $slot }}
</x-ui.tabs>
