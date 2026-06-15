@props([
    'title' => 'Quick actions',
    'actions' => [],
])

<x-ui.card :title="$title" icon="bolt" {{ $attributes }}>
    <div class="grid gap-3 sm:grid-cols-2">
        @foreach ($actions as $action)
            <a href="{{ $action['url'] ?? '#' }}" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <x-ui.icon :name="$action['icon'] ?? 'plus'" class="h-4 w-4" />
                </span>
                {{ $action['label'] ?? '' }}
            </a>
        @endforeach
    </div>
</x-ui.card>
