@php
    $navigation = $navigation ?? [];
    $mobile = $mobile ?? false;
@endphp

<nav class="space-y-1" aria-label="{{ $mobile ? 'Mobile sidebar' : 'Sidebar' }}">
    @foreach ($navigation as $item)
        @if (isset($item['children']))
            @php $groupKey = Illuminate\Support\Str::slug($item['label']); @endphp
            <div x-data="{ open: @js($item['active'] ?? false) }" class="space-y-1">
                <button
                    type="button"
                    x-on:click="open = ! open; collapsed && (collapsed = false)"
                    class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-start text-sm font-medium transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 {{ ($item['active'] ?? false) ? 'bg-slate-100 text-slate-950 dark:bg-slate-900 dark:text-white' : 'text-slate-600 hover:bg-white hover:text-slate-950 hover:shadow-sm dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white' }}"
                    aria-controls="sidebar-group-{{ $groupKey }}"
                    x-bind:aria-expanded="open"
                >
                    <x-ui.icon :name="$item['icon'] ?? 'chevron-right'" class="h-5 w-5 shrink-0" />
                    <span x-bind:class="@js($mobile) ? '' : (collapsed ? 'lg:sr-only' : '')" class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                    <x-ui.icon name="chevron-down" x-bind:class="[(open ? 'rotate-180' : ''), (! @js($mobile) && collapsed ? 'lg:hidden' : '')]" class="h-4 w-4 transition" />
                </button>

                <div
                    id="sidebar-group-{{ $groupKey }}"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="space-y-1 ps-4"
                >
                    @foreach ($item['children'] as $child)
                        <x-nav-link
                            variant="sidebar"
                            :href="$child['href'] ?? '#'"
                            :active="$child['active'] ?? false"
                            :icon="$child['icon'] ?? 'chevron-right'"
                            :badge="$child['badge'] ?? null"
                            :collapsed="! $mobile"
                            x-on:click="sidebarOpen = false"
                        >
                            {{ $child['label'] }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>
        @else
            <x-nav-link
                variant="sidebar"
                :href="$item['href'] ?? '#'"
                :active="$item['active'] ?? false"
                :icon="$item['icon'] ?? 'chevron-right'"
                :badge="$item['badge'] ?? null"
                :collapsed="! $mobile"
                x-on:click="sidebarOpen = false"
            >
                {{ $item['label'] }}
            </x-nav-link>
        @endif
    @endforeach
</nav>
