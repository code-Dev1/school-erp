@props([
    'page' => 1,
    'pages' => 1,
    'paginator' => null,
])

@if ($paginator)
    <div {{ $attributes }}>
        {{ $paginator->links() }}
    </div>
@else
    <nav {{ $attributes->merge(['class' => 'flex items-center justify-between gap-3']) }} aria-label="Pagination">
        <x-ui.button variant="secondary" size="sm" :disabled="$page <= 1">Previous</x-ui.button>
        <div class="flex items-center gap-1">
            @for ($number = 1; $number <= $pages; $number++)
                <button type="button" @class([
                    'h-9 min-w-9 rounded-lg px-3 text-sm font-medium transition ui-focus',
                    'bg-slate-950 text-white dark:bg-white dark:text-slate-950' => $number === $page,
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => $number !== $page,
                ])>{{ $number }}</button>
            @endfor
        </div>
        <x-ui.button variant="secondary" size="sm" :disabled="$page >= $pages">Next</x-ui.button>
    </nav>
@endif
