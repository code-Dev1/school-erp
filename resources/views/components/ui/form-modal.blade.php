@props([
    'name',
    'title',
    'submit' => null,
    'size' => 'lg',
])

<x-ui.modal :name="$name" :title="$title" :size="$size" {{ $attributes }}>
    <form @if ($submit) wire:submit="{{ $submit }}" @endif class="space-y-5">
        {{ $slot }}

        <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5 dark:border-slate-800">
            {{ $footer ?? '' }}
        </div>
    </form>
</x-ui.modal>
