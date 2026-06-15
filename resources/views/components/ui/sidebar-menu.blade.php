@props([
    'brand' => config('app.name', 'Laravel'),
    'items' => [],
])

<aside {{ $attributes->merge(['class' => 'flex h-full w-72 flex-col border-e border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950']) }}>
    <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5 dark:border-slate-800">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white dark:bg-white dark:text-slate-950">
            <x-ui.icon name="sparkles" class="h-5 w-5" />
        </span>
        <span class="text-sm font-semibold text-slate-950 dark:text-white">{{ $brand }}</span>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-3 ui-scrollbar" aria-label="Sidebar">
        @foreach ($items as $item)
            <a href="{{ $item['url'] ?? '#' }}" @class([
                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                'bg-slate-100 text-slate-950 dark:bg-slate-800 dark:text-white' => $item['active'] ?? false,
                'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => ! ($item['active'] ?? false),
            ])>
                <x-ui.icon :name="$item['icon'] ?? 'chevron-right'" class="h-4 w-4" />
                <span class="min-w-0 flex-1 truncate">{{ $item['label'] ?? '' }}</span>
                @if ($item['badge'] ?? null)
                    <x-ui.badge variant="count">{{ $item['badge'] }}</x-ui.badge>
                @endif
            </a>
        @endforeach
    </nav>
</aside>
