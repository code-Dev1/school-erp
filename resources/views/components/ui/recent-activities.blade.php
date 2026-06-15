@props([
    'items' => [],
])

<x-ui.activity-card :items="$items" {{ $attributes }} />
