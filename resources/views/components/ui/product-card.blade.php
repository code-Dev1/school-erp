@props([
    'name',
    'description' => null,
    'price' => null,
    'image' => null,
    'status' => null,
])

<x-ui.card padding="p-0" {{ $attributes }}>
    <div class="aspect-[4/3] overflow-hidden rounded-t-lg bg-slate-100 dark:bg-slate-900">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center text-slate-400">
                <x-ui.icon name="photo" class="h-10 w-10" />
            </div>
        @endif
    </div>

    <div class="p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-950 dark:text-white">{{ $name }}</h3>
                @if ($description)
                    <p class="mt-1 line-clamp-2 text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $description }}</p>
                @endif
            </div>
            @if ($status)
                <x-ui.badge variant="status">{{ $status }}</x-ui.badge>
            @endif
        </div>

        @if ($price || trim($slot))
            <div class="mt-4 flex items-center justify-between gap-3">
                @if ($price)
                    <p class="text-lg font-semibold text-slate-950 dark:text-white">{{ $price }}</p>
                @endif
                {{ $slot }}
            </div>
        @endif
    </div>
</x-ui.card>
