@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' => 'flex']) }} aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 text-sm">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <x-ui.icon name="chevron-right" class="h-4 w-4 text-slate-400 rtl:rotate-180" />
                @endif
                @if (($item['url'] ?? null) && ! $loop->last)
                    <a href="{{ $item['url'] }}" class="font-medium text-slate-500 transition hover:text-slate-950 dark:text-slate-400 dark:hover:text-white">{{ $item['label'] }}</a>
                @else
                    <span class="font-medium text-slate-950 dark:text-white">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
