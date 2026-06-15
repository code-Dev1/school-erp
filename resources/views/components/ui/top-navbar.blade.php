@props([
    'brand' => config('app.name', 'Laravel'),
    'links' => [],
])

<header {{ $attributes->merge(['class' => 'sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/85']) }}>
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-8">
            <a href="/" class="flex items-center gap-3 font-semibold text-slate-950 dark:text-white">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-950 text-white dark:bg-white dark:text-slate-950">
                    <x-ui.icon name="sparkles" class="h-5 w-5" />
                </span>
                {{ $brand }}
            </a>
            <nav class="hidden items-center gap-1 md:flex" aria-label="Primary">
                @foreach ($links as $link)
                    <a href="{{ $link['url'] ?? '#' }}" @class([
                        'rounded-lg px-3 py-2 text-sm font-medium transition',
                        'bg-slate-100 text-slate-950 dark:bg-slate-800 dark:text-white' => $link['active'] ?? false,
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => ! ($link['active'] ?? false),
                    ])>{{ $link['label'] ?? '' }}</a>
                @endforeach
            </nav>
        </div>
        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>
    </div>
</header>
