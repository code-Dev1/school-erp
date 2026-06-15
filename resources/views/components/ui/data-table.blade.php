@props([
    'title' => null,
    'description' => null,
    'columns' => [],
    'rows' => [],
    'rowKey' => 'id',
    'actions' => [],
    'searchable' => true,
    'filterable' => true,
    'selectable' => true,
    'exportable' => true,
    'sticky' => false,
    'loading' => false,
    'loadingTarget' => null,
    'emptyTitle' => 'No records found',
    'emptyDescription' => 'Try changing your filters or add a new record.',
    'exportFilename' => 'table-export.csv',
])

@php
    $normalizedColumns = collect($columns)->map(function ($column) {
        return [
            'key' => $column['key'],
            'label' => $column['label'] ?? Illuminate\Support\Str::headline($column['key']),
            'sortable' => $column['sortable'] ?? true,
            'filterable' => $column['filterable'] ?? false,
        ];
    })->values();

    $normalizedRows = collect($rows)->values()->map(function ($row, $index) use ($normalizedColumns, $rowKey) {
        $record = [$rowKey => data_get($row, $rowKey, $index + 1)];

        foreach ($normalizedColumns as $column) {
            $record[$column['key']] = data_get($row, $column['key']);
        }

        return $record;
    })->values();
@endphp

<section
    x-data="uiDataTable({ rows: @js($normalizedRows), columns: @js($normalizedColumns), rowKey: @js($rowKey) })"
    {{ $attributes->merge(['class' => 'ui-surface overflow-hidden rounded-lg']) }}
>
    @if ($title || $description || $searchable || $exportable || isset($toolbar))
        <div class="border-b border-slate-200 p-4 dark:border-slate-800">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    @if ($title)
                        <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h3>
                    @endif
                    @if ($description)
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
                    @endif
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    @if ($searchable)
                        <div class="relative">
                            <x-ui.icon name="magnifying-glass" class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                            <input x-model.debounce.200ms="search" type="search" placeholder="Search" class="ui-field w-full ps-10 sm:w-64">
                        </div>
                    @endif

                    @isset($toolbar)
                        {{ $toolbar }}
                    @endisset

                    @if ($exportable)
                        <x-ui.button variant="secondary" icon="arrow-down-tray" x-on:click="exportCsv(@js($exportFilename))">Export</x-ui.button>
                    @endif
                </div>
            </div>

            @if ($filterable && $normalizedColumns->contains('filterable', true))
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($normalizedColumns->where('filterable', true) as $column)
                        @php $values = $normalizedRows->pluck($column['key'])->filter(fn ($value) => filled($value))->unique()->values(); @endphp
                        <select x-model="filters[@js($column['key'])]" class="ui-field w-auto min-w-40 py-1.5 text-xs">
                            <option value="">All {{ $column['label'] }}</option>
                            @foreach ($values as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if ($selectable)
        <div x-show="selected.length" x-cloak class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="font-medium text-slate-700 dark:text-slate-200"><span x-text="selected.length"></span> selected</span>
            <div class="flex items-center gap-2">
                <x-ui.button variant="ghost" size="sm" x-on:click="selected = []">Clear</x-ui.button>
                {{ $bulkActions ?? '' }}
            </div>
        </div>
    @endif

    <div class="relative">
        @if ($loading)
            <div class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 backdrop-blur-sm dark:bg-slate-950/70">
                <x-ui.spinner label="Loading records" />
            </div>
        @endif

        @if ($loadingTarget)
            <div wire:loading.flex wire:target="{{ $loadingTarget }}" class="absolute inset-0 z-20 hidden items-center justify-center bg-white/70 backdrop-blur-sm dark:bg-slate-950/70">
                <x-ui.spinner label="Loading records" />
            </div>
        @endif

        <div class="hidden overflow-x-auto md:block ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead @class([
                    'bg-slate-50 dark:bg-slate-900',
                    'sticky top-0 z-10' => $sticky,
                ])>
                    <tr>
                        @if ($selectable)
                            <th scope="col" class="w-12 px-4 py-3">
                                <input type="checkbox" class="rounded border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400" x-bind:checked="visibleRows.length && selected.length === visibleRows.length" x-on:change="toggleAll($event.target.checked)">
                            </th>
                        @endif
                        <template x-for="column in columns" :key="column.key">
                            <th scope="col" class="px-4 py-3 text-start font-semibold text-slate-600 dark:text-slate-300">
                                <button type="button" x-on:click="sort(column)" class="inline-flex items-center gap-1 rounded-md text-start ui-focus" x-bind:class="column.sortable ? 'cursor-pointer hover:text-slate-950 dark:hover:text-white' : 'cursor-default'">
                                    <span x-text="column.label"></span>
                                    <x-ui.icon name="chevron-down" class="h-3.5 w-3.5 transition" x-bind:class="sortKey === column.key && sortDirection === 'desc' ? 'rotate-180' : ''" x-show="column.sortable" />
                                </button>
                            </th>
                        </template>
                        @if (count($actions))
                            <th scope="col" class="w-16 px-4 py-3 text-end font-semibold text-slate-600 dark:text-slate-300">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    <template x-for="row in visibleRows" :key="row[rowKey]">
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900">
                            @if ($selectable)
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="rounded border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400" x-model="selected" x-bind:value="row[rowKey]">
                                </td>
                            @endif
                            <template x-for="column in columns" :key="column.key">
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200" x-text="row[column.key]"></td>
                            </template>
                            @if (count($actions))
                                <td class="px-4 py-3 text-end">
                                    <x-ui.dropdown align="end" width="w-44">
                                        <x-slot name="trigger">
                                            <x-ui.icon-button icon="ellipsis-vertical" label="Row actions" size="sm" />
                                        </x-slot>
                                        @foreach ($actions as $action)
                                            <button
                                                type="button"
                                                class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-start text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                                                x-on:click="
                                                    @if ($action['event'] ?? null)
                                                        $dispatch(@js($action['event']), { row });
                                                    @elseif ($action['url'] ?? null)
                                                        window.location.href = @js($action['url']).replace('{id}', row[rowKey]);
                                                    @endif
                                                "
                                            >
                                                <x-ui.icon :name="$action['icon'] ?? 'chevron-right'" class="h-4 w-4" />
                                                {{ $action['label'] ?? 'Action' }}
                                            </button>
                                        @endforeach
                                    </x-ui.dropdown>
                                </td>
                            @endif
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-slate-100 md:hidden dark:divide-slate-800">
            <template x-for="row in visibleRows" :key="row[rowKey]">
                <article class="space-y-3 p-4">
                    <div class="flex items-center justify-between gap-3">
                        @if ($selectable)
                            <input type="checkbox" class="rounded border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400" x-model="selected" x-bind:value="row[rowKey]">
                        @endif
                        <x-ui.badge variant="neutral">#<span x-text="row[rowKey]"></span></x-ui.badge>
                    </div>
                    <template x-for="column in columns" :key="column.key">
                        <div class="flex justify-between gap-4 text-sm">
                            <dt class="font-medium text-slate-500 dark:text-slate-400" x-text="column.label"></dt>
                            <dd class="text-end font-medium text-slate-900 dark:text-slate-100" x-text="row[column.key]"></dd>
                        </div>
                    </template>
                </article>
            </template>
        </div>

        <div x-show="visibleRows.length === 0" x-cloak class="flex flex-col items-center justify-center px-6 py-14 text-center">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-900 dark:text-slate-500">
                <x-ui.icon name="magnifying-glass" class="h-6 w-6" />
            </span>
            <h3 class="mt-4 text-sm font-semibold text-slate-950 dark:text-white">{{ $emptyTitle }}</h3>
            <p class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">{{ $emptyDescription }}</p>
        </div>
    </div>

    @isset($pagination)
        <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">
            {{ $pagination }}
        </div>
    @endisset
</section>
