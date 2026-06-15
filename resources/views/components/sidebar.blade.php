@props([
    'brand' => 'سامانه آموزشی',
])

@php
    $user = auth()->user();
    $userName = $user?->name ?? 'مدیر سیستم';
    $userEmail = $user?->email ?? 'admin@example.com';
    $initials = Illuminate\Support\Str::of($userName)->explode(' ')->map(fn ($part) => Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('');
    $safeRoute = fn (string $name) => Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';

    $navigation = [
        ['label' => 'داشبورد', 'href' => route('dashboard'), 'icon' => 'home', 'active' => request()->routeIs('dashboard')],
        [
            'label' => 'مدیریت شاگردان',
            'icon' => 'academic-cap',
            'active' => request()->routeIs('student-index') || request()->routeIs('student-create') || request()->routeIs('guardian-index') || request()->routeIs('guardian-create') || request()->routeIs('marks.*'),
            'children' => [
                ['label' => 'شاگردان', 'href' => $safeRoute('student-index'), 'icon' => 'academic-cap', 'active' => request()->routeIs('student-index')],
                ['label' => 'سرپرستان', 'href' => $safeRoute('guardian-index'), 'icon' => 'users', 'active' => request()->routeIs('guardian-index')],
                // ['label' => 'نمرات و نتایج', 'href' => $safeRoute('marks.index'), 'icon' => 'document-chart-bar', 'active' => request()->routeIs('marks.*')],
            ],
        ],
        [
            'label' => 'مدیریت آموزشی',
            'icon' => 'book-open',
            'active' => request()->routeIs('classes.*') || request()->routeIs('academic-years.*') || request()->routeIs('subjects.*') || request()->routeIs('timetables.*'),
            'children' => [
                ['label' => 'صنف‌ها', 'href' => $safeRoute('classes.index'), 'icon' => 'building-office', 'active' => request()->routeIs('classes.*')],
                ['label' => 'مضامین', 'href' => $safeRoute('subjects.index'), 'icon' => 'book-open', 'active' => request()->routeIs('subjects.*')],
                ['label' => 'تقسیم اوقات', 'href' => $safeRoute('timetables.index'), 'icon' => 'calendar-days', 'active' => request()->routeIs('timetables.*')],
                ['label' => 'سال تعلیمی', 'href' => $safeRoute('academic-years.index'), 'icon' => 'calendar-days', 'active' => request()->routeIs('academic-years.*')],
            ],
        ],
        [
            'label' => 'استادان و کارمندان',
            'icon' => 'identification',
            'active' => request()->routeIs('teachers.*') || request()->routeIs('staff.*') || request()->routeIs('payroll.*') || request()->routeIs('attendance.staff*'),
            'children' => [
                ['label' => 'استادان', 'href' => $safeRoute('teachers.index'), 'icon' => 'users', 'active' => request()->routeIs('teachers.*')],
                ['label' => 'کارمندان', 'href' => $safeRoute('staff.index'), 'icon' => 'identification', 'active' => request()->routeIs('staff.*')],            ],
        ],
        [
            'label' => 'حاضری و بیومتریک',
            'icon' => 'finger-print',
            'active' => request()->routeIs('attendance.*') || request()->routeIs('biometric.*'),
            'children' => [
                ['label' => 'حاضری شاگردان', 'href' => $safeRoute('attendance.students'), 'icon' => 'check', 'active' => request()->routeIs('attendance.students*')],
                ['label' => 'حاضری کارمندان', 'href' => $safeRoute('attendance.staff'), 'icon' => 'clock', 'active' => request()->routeIs('attendance.staff*')],
                ['label' => 'دستگاه‌های بیومتریک', 'href' => $safeRoute('biometric.devices.index'), 'icon' => 'cog-6-tooth', 'active' => request()->routeIs('biometric.devices.*')],
                ['label' => 'لاگ‌های بیومتریک', 'href' => $safeRoute('biometric.logs.index'), 'icon' => 'document-chart-bar', 'active' => request()->routeIs('biometric.logs.*')],
            ],
        ],
        [
            'label' => 'امتحانات',
            'icon' => 'document-chart-bar',
            'active' => request()->routeIs('exams.*') || request()->routeIs('marks.*') || request()->routeIs('reports.exams'),
            'children' => [
                ['label' => 'گزارش امتحانات', 'href' => $safeRoute('reports.exams'), 'icon' => 'calendar-days', 'active' => request()->routeIs('reports.exams')],
                ['label' => 'ثبت نمرات', 'href' => $safeRoute('marks.create'), 'icon' => 'pencil-square', 'active' => request()->routeIs('marks.create')],
                ['label' => 'کارنامه شاگردان', 'href' => $safeRoute('marks.index'), 'icon' => 'document-chart-bar', 'active' => request()->routeIs('marks.index')],
            ],
        ],
        [
            'label' => 'مالی',
            'icon' => 'banknotes',
            'active' => request()->routeIs('expenses.*') || request()->routeIs('fees.*') || request()->routeIs('payroll.*') || request()->routeIs('reports.finance'),
            'children' => [
                ['label' => 'فیس شاگردان', 'href' => $safeRoute('fees.index'), 'icon' => 'currency-dollar', 'active' => request()->routeIs('fees.*')],
                ['label' => 'مصارف روزانه', 'href' => $safeRoute('expenses.index'), 'icon' => 'banknotes', 'active' => request()->routeIs('expenses.*')],
                ['label' => 'معاشات', 'href' => $safeRoute('payroll.index'), 'icon' => 'currency-dollar', 'active' => request()->routeIs('payroll.*')],
                ['label' => 'گزارش مالی', 'href' => $safeRoute('reports.finance'), 'icon' => 'chart-bar', 'active' => request()->routeIs('reports.finance')],
            ],
        ],
        [
            'label' => 'ترانسپورت',
            'icon' => 'truck',
            'active' => request()->routeIs('transport.*'),
            'children' => [
                ['label' => 'موتر، راننده و مسیر', 'href' => $safeRoute('transport.index'), 'icon' => 'truck', 'active' => request()->routeIs('transport.index') || request()->routeIs('transport.create') || request()->routeIs('transport.show') || request()->routeIs('transport.edit')],
                ['label' => 'ترانسپورت شاگردان', 'href' => $safeRoute('transport.assignments.index'), 'icon' => 'academic-cap', 'active' => request()->routeIs('transport.assignments.*')],
            ],
        ],
        [
            'label' => 'فروش و انبار',
            'icon' => 'rectangle-stack',
            'active' => request()->routeIs('sales.*'),
            'children' => [
                ['label' => 'آیتم‌های فروش', 'href' => $safeRoute('sales.items.index'), 'icon' => 'rectangle-stack', 'active' => request()->routeIs('sales.items.*')],
                ['label' => 'فروش شاگردان', 'href' => $safeRoute('sales.index'), 'icon' => 'banknotes', 'active' => request()->routeIs('sales.*') && ! request()->routeIs('sales.items.*')],
            ],
        ],
        [
            'label' => 'کتابخانه و منابع',
            'icon' => 'book-open',
            'active' => request()->routeIs('library.*') || request()->routeIs('resources.*'),
            'children' => [
                ['label' => 'کتاب‌ها', 'href' => $safeRoute('library.books.index'), 'icon' => 'book-open', 'active' => request()->routeIs('library.books.*')],
                ['label' => 'امانت کتاب', 'href' => $safeRoute('library.loans.index'), 'icon' => 'arrow-path', 'active' => request()->routeIs('library.loans.*')],
                ['label' => 'مواد درسی', 'href' => $safeRoute('library.materials.index'), 'icon' => 'rectangle-stack', 'active' => request()->routeIs('library.materials.*')],
            ],
        ],
        [
            'label' => 'گزارش‌ها',
            'icon' => 'chart-bar',
            'active' => request()->routeIs('reports.*'),
            'children' => [
                ['label' => 'تاریخچه گزارش ها', 'href' => $safeRoute('reports.index'), 'icon' => 'chart-bar', 'active' => request()->routeIs('reports.index')],
                ['label' => 'گزارش شاگردان', 'href' => $safeRoute('reports.students'), 'icon' => 'academic-cap', 'active' => request()->routeIs('reports.students')],
                ['label' => 'گزارش حاضری', 'href' => $safeRoute('reports.attendance'), 'icon' => 'check', 'active' => request()->routeIs('reports.attendance')],
                ['label' => 'گزارش مالی', 'href' => $safeRoute('reports.finance'), 'icon' => 'banknotes', 'active' => request()->routeIs('reports.finance')],
                ['label' => 'گزارش امتحانات', 'href' => $safeRoute('reports.exams'), 'icon' => 'document-chart-bar', 'active' => request()->routeIs('reports.exams')],
            ],
        ],
        [
            'label' => 'تنظیمات سیستم',
            'icon' => 'cog-6-tooth',
            'active' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('settings.*'),
            'children' => [
                ['label' => 'کاربران', 'href' => $safeRoute('users.index'), 'icon' => 'users', 'active' => request()->routeIs('users.*')],
                ['label' => 'نقش‌ها', 'href' => $safeRoute('roles.index'), 'icon' => 'shield-check', 'active' => request()->routeIs('roles.*')],
                ['label' => 'صلاحیت‌ها', 'href' => $safeRoute('permissions.index'), 'icon' => 'lock-closed', 'active' => request()->routeIs('permissions.*')],
                ['label' => 'تنظیمات عمومی', 'href' => $safeRoute('settings.index'), 'icon' => 'cog-6-tooth', 'active' => request()->routeIs('settings.*')],
            ],
        ],
    ];
@endphp

@once
    @push('scripts')
        <script>
            document.addEventListener('keydown', function (event) {
                if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                    event.preventDefault();
                    document.getElementById('admin-global-search')?.focus();
                }
            });
        </script>
    @endpush
@endonce

<div>
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        x-on:click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/55 backdrop-blur-sm lg:hidden"
    ></div>

    <aside
        x-cloak
        x-show="sidebarOpen"
        x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 z-50 flex w-80 max-w-[calc(100vw-2rem)] flex-col overflow-hidden border-l border-white/50 bg-slate-50/95 shadow-[0_24px_70px_rgba(15,23,42,0.28)] backdrop-blur-2xl dark:border-slate-800/80 dark:bg-slate-950/95 dark:shadow-black/50 lg:hidden"
    >
        <div class="border-b border-white/70 bg-white/70 px-4 py-4 shadow-sm shadow-slate-950/[0.03] dark:border-slate-800 dark:bg-slate-950/70">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex min-w-0 items-center gap-3 rounded-2xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/15 dark:bg-white dark:text-slate-950">
                    <x-ui.icon name="sparkles" class="h-5 w-5" />
                </span>
                    <span class="truncate text-sm font-bold text-slate-950 dark:text-white">{{ $brand }}</span>
                </a>
                <button type="button" x-on:click="sidebarOpen = false" class="rounded-2xl border border-slate-200 bg-white p-2 text-slate-500 shadow-sm transition hover:border-slate-300 hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:text-white">
                    <x-ui.icon name="x-mark" class="h-5 w-5" />
                    <span class="sr-only">بستن نوار کناری</span>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-4 ui-scrollbar">
            <nav class="space-y-1" aria-label="فهرست موبایل">
                @foreach ($navigation as $item)
                    @if (isset($item['children']))
                        @php($isExpanded = $item['expanded'] ?? ($item['active'] ?? false))
                        <div x-data="{ open: @js($isExpanded) }" class="space-y-1">
                            <button
                                type="button"
                                x-on:click="open = ! open"
                                class="group flex min-h-11 w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-right text-sm font-semibold transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50"
                                x-bind:class="open ? 'bg-white text-slate-950 shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900/80 dark:text-white dark:ring-slate-800' : 'text-slate-600 hover:bg-white/85 hover:text-slate-950 hover:shadow-sm dark:text-slate-300 dark:hover:bg-slate-900/85 dark:hover:text-white'"
                                x-bind:aria-expanded="open"
                            >
                                <x-ui.icon :name="$item['icon']" class="h-5 w-5 shrink-0 opacity-90" />
                                <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                                <x-ui.icon name="chevron-down" class="h-4 w-4 transition" x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div
                                x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="relative mt-1 space-y-1 pr-5 before:absolute before:bottom-2 before:right-2 before:top-2 before:w-px before:bg-slate-200 dark:before:bg-slate-800"
                            >
                                @foreach ($item['children'] as $child)
                                    <x-nav-link :href="$child['href']" :active="$child['active'] ?? false" :icon="$child['icon']" :collapsed="false" x-on:click="sidebarOpen = false">
                                        {{ $child['label'] }}
                                    </x-nav-link>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <x-nav-link :href="$item['href']" :active="$item['active'] ?? false" :icon="$item['icon']" :badge="$item['badge'] ?? null" :collapsed="false" x-on:click="sidebarOpen = false">
                            {{ $item['label'] }}
                        </x-nav-link>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="border-t border-white/70 bg-white/60 p-4 dark:border-slate-800 dark:bg-slate-950/60">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-3 shadow-lg shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-900/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-600 to-sky-500 text-sm font-semibold text-white">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $userName }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <aside class="admin-sidebar fixed inset-y-0 right-0 z-30 hidden overflow-hidden border-l border-white/60 bg-slate-50/95 shadow-[0_18px_55px_rgba(15,23,42,0.20)] backdrop-blur-2xl transition-[width] duration-300 ease-out dark:border-slate-800/80 dark:bg-slate-950/95 dark:shadow-black/50 lg:flex lg:flex-col">
        <div class="border-b border-white/70 bg-white/70 px-4 py-4 shadow-sm shadow-slate-950/[0.03] dark:border-slate-800 dark:bg-slate-950/70">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex min-w-0 items-center gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/15 dark:bg-white dark:text-slate-950">
                    <x-ui.icon name="sparkles" class="h-5 w-5" />
                </span>
                <span class="admin-collapse-hide truncate text-sm font-bold text-slate-950 transition dark:text-white">{{ $brand }}</span>
            </a>
        </div>

        <div class="px-3 pt-4">
            <button
                type="button"
                x-on:click="toggleSidebar()"
                class="flex min-h-11 w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-white hover:text-slate-950 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:text-white"
            >
                <x-ui.icon name="bars-3" class="h-4 w-4" />
                <span class="admin-collapse-hide">جمع‌کردن فهرست</span>
            </button>
        </div>

        <div class="mt-3 flex-1 overflow-y-auto px-3 pb-4 ui-scrollbar">
            <nav class="space-y-1" aria-label="فهرست اصلی">
                @foreach ($navigation as $item)
                    @if (isset($item['children']))
                        @php($isExpanded = $item['expanded'] ?? ($item['active'] ?? false))
                        <div x-data="{ open: @js($isExpanded) }" class="space-y-1">
                            <button
                                type="button"
                                x-on:click="open = ! open; collapsed && toggleSidebar()"
                                class="group flex min-h-11 w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-right text-sm font-semibold transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50"
                                x-bind:class="open ? 'bg-white text-slate-950 shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900/80 dark:text-white dark:ring-slate-800' : 'text-slate-600 hover:bg-white/85 hover:text-slate-950 hover:shadow-sm dark:text-slate-300 dark:hover:bg-slate-900/85 dark:hover:text-white'"
                                x-bind:aria-expanded="open"
                            >
                                <x-ui.icon :name="$item['icon']" class="h-5 w-5 shrink-0 opacity-90" />
                                <span class="admin-collapse-hide min-w-0 flex-1 truncate">{{ $item['label'] }}</span>
                                <x-ui.icon name="chevron-down" class="admin-collapse-hide h-4 w-4 transition" x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div
                                x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="relative mt-1 space-y-1 pr-5 before:absolute before:bottom-2 before:right-2 before:top-2 before:w-px before:bg-slate-200 dark:before:bg-slate-800"
                            >
                                @foreach ($item['children'] as $child)
                                    <x-nav-link :href="$child['href']" :active="$child['active'] ?? false" :icon="$child['icon']" :collapsed="true">
                                        {{ $child['label'] }}
                                    </x-nav-link>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <x-nav-link :href="$item['href']" :active="$item['active'] ?? false" :icon="$item['icon']" :badge="$item['badge'] ?? null" :collapsed="true">
                            {{ $item['label'] }}
                        </x-nav-link>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="border-t border-white/70 bg-white/60 p-3 dark:border-slate-800 dark:bg-slate-950/60">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-3 shadow-lg shadow-slate-950/[0.04] transition dark:border-slate-800 dark:bg-slate-900/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-600 to-sky-500 text-sm font-semibold text-white">
                        {{ $initials }}
                    </div>
                    <div class="admin-collapse-hide min-w-0 transition">
                        <p class="truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $userName }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</div>
