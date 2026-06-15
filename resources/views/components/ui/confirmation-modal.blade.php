@props([
    'name',
    'title' => 'Confirm action',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'danger',
])

<x-ui.modal :name="$name" :title="$title" size="md" {{ $attributes }}>
    <div class="text-sm leading-6 text-slate-600 dark:text-slate-300">
        {{ $slot }}
    </div>

    <x-slot name="footer">
        <x-ui.button variant="secondary" x-on:click="$dispatch('close-modal', @js($name))">{{ $cancelText }}</x-ui.button>
        <x-ui.button
            :variant="$variant"
            type="button"
            x-on:click="$dispatch('confirmed', { name: @js($name) }); $dispatch('close-modal', @js($name))"
        >
            {{ $confirm ?? $confirmText }}
        </x-ui.button>
    </x-slot>
</x-ui.modal>
