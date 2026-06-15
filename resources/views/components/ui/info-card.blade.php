@props([
    'title',
    'description' => null,
    'icon' => 'information-circle',
])

<x-ui.card :title="$title" :subtitle="$description" :icon="$icon" {{ $attributes }}>
    {{ $slot }}
</x-ui.card>
