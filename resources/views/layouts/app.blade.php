<x-layouts.app :title="$title ?? 'داشبورد'" :breadcrumbs="$breadcrumbs ?? []">
    @isset($header)
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endisset

    @isset($actions)
        <x-slot name="actions">
            {{ $actions }}
        </x-slot>
    @endisset

    {{ $slot }}
</x-layouts.app>
