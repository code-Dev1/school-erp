@props([
    'variant' => 'primary',
    'target' => null,
    'loadingText' => 'Loading',
    'icon' => null,
])

@if ($target)
    <x-ui.button
        :variant="$variant"
        :icon="$icon"
        {{ $attributes->merge(['type' => 'submit']) }}
        wire:target="{{ $target }}"
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove wire:target="{{ $target }}">
            {{ $slot }}
        </span>
        <span class="hidden items-center gap-2" wire:loading.flex wire:target="{{ $target }}">
            <x-ui.icon name="arrow-path" class="h-4 w-4 animate-spin" />
            {{ $loadingText }}
        </span>
    </x-ui.button>
@else
    <x-ui.button
        :variant="$variant"
        :icon="$icon"
        {{ $attributes->merge(['type' => 'submit']) }}
        wire:loading.attr="disabled"
    >
        <span wire:loading.remove>
            {{ $slot }}
        </span>
        <span class="hidden items-center gap-2" wire:loading.flex>
            <x-ui.icon name="arrow-path" class="h-4 w-4 animate-spin" />
            {{ $loadingText }}
        </span>
    </x-ui.button>
@endif
