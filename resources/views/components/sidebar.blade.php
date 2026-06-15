@props([
    'brand' => 'مدیریت سیستم آموزشگاه',
])

@php
    $user      = auth()->user();
    $userName  = $user?->name ?? 'مدیر سیستم';
    $userEmail = $user?->email ?? 'admin@example.com';
    $initials  = Illuminate\Support\Str::of($userName)
                    ->explode(' ')
                    ->map(fn ($p) => Illuminate\Support\Str::substr($p, 0, 1))
                    ->take(2)
                    ->implode('');

    $safeRoute = fn (string $name) =>
        Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';

    $navigation = [
        [
            'label'  => 'صفخه اصلی',
            'href'   => route('dashboard'),
            'icon'   => 'home',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label'  => 'مدیریت شاگردان',
            'icon'   => 'academic-cap',
            'active' => request()->routeIs('student-index', 'student-create', 'guardian-index', 'guardian-create', 'marks.*'),
            'children' => [
                ['label' => 'شاگردان',  'href' => $safeRoute('student-index'),  'icon' => 'academic-cap', 'active' => request()->routeIs('student-index')],
                ['label' => 'سرپرستان', 'href' => $safeRoute('guardian-index'), 'icon' => 'users',        'active' => request()->routeIs('guardian-index')],
            ],
        ],
        [
            'label'  => 'مدیریت آموزشی',
            'icon'   => 'book-open',
            'active' => request()->routeIs('classes.*', 'academic-years.*', 'subjects.*', 'timetables.*'),
            'children' => [
                ['label' => 'صنف‌ها',      'href' => $safeRoute('classes.index'),        'icon' => 'building-office', 'active' => request()->routeIs('classes.*')],
                ['label' => 'مضامین',      'href' => $safeRoute('subjects.index'),       'icon' => 'book-open',      'active' => request()->routeIs('subjects.*')],
                ['label' => 'تقسیم اوقات', 'href' => $safeRoute('timetables.index'),     'icon' => 'calendar-days',  'active' => request()->routeIs('timetables.*')],
                ['label' => 'سال تعلیمی',  'href' => $safeRoute('academic-years.index'), 'icon' => 'calendar-days',  'active' => request()->routeIs('academic-years.*')],
            ],
        ],
        [
            'label'  => 'استادان و کارمندان',
            'icon'   => 'identification',
            'active' => request()->routeIs('teachers.*', 'staff.*'),
            'children' => [
                ['label' => 'استادان',  'href' => $safeRoute('teachers.index'), 'icon' => 'users',          'active' => request()->routeIs('teachers.*')],
                ['label' => 'کارمندان', 'href' => $safeRoute('staff.index'),    'icon' => 'identification', 'active' => request()->routeIs('staff.*')],
            ],
        ],
        [
            'label'  => 'حاضری و بیومتریک',
            'icon'   => 'finger-print',
            'active' => request()->routeIs('attendance.*', 'biometric.*'),
            'children' => [
                ['label' => 'حاضری شاگردان',     'href' => $safeRoute('attendance.students'),       'icon' => 'check',              'active' => request()->routeIs('attendance.students*')],
                ['label' => 'حاضری کارمندان',     'href' => $safeRoute('attendance.staff'),          'icon' => 'clock',              'active' => request()->routeIs('attendance.staff*')],
                ['label' => 'دستگاه‌های بیومتریک', 'href' => $safeRoute('biometric.devices.index'), 'icon' => 'cog-6-tooth',        'active' => request()->routeIs('biometric.devices.*')],
                ['label' => 'لاگ‌های بیومتریک',   'href' => $safeRoute('biometric.logs.index'),    'icon' => 'document-chart-bar', 'active' => request()->routeIs('biometric.logs.*')],
            ],
        ],
        [
            'label'  => 'امتحانات',
            'icon'   => 'document-chart-bar',
            'active' => request()->routeIs('exams.*', 'marks.*', 'reports.exams'),
            'children' => [
                ['label' => 'گزارش امتحانات',  'href' => $safeRoute('reports.exams'),  'icon' => 'calendar-days',      'active' => request()->routeIs('reports.exams')],
                ['label' => 'ثبت نمرات',       'href' => $safeRoute('marks.create'),   'icon' => 'pencil-square',      'active' => request()->routeIs('marks.create')],
                ['label' => 'کارنامه شاگردان', 'href' => $safeRoute('marks.index'),    'icon' => 'document-chart-bar', 'active' => request()->routeIs('marks.index')],
            ],
        ],
        [
            'label'  => 'مالی',
            'icon'   => 'banknotes',
            'active' => request()->routeIs('expenses.*', 'fees.*', 'payroll.*', 'reports.finance'),
            'children' => [
                ['label' => 'فیس شاگردان',  'href' => $safeRoute('fees.index'),      'icon' => 'currency-dollar', 'active' => request()->routeIs('fees.*')],
                ['label' => 'مصارف روزانه', 'href' => $safeRoute('expenses.index'),  'icon' => 'banknotes',       'active' => request()->routeIs('expenses.*')],
                ['label' => 'معاشات',        'href' => $safeRoute('payroll.index'),   'icon' => 'currency-dollar', 'active' => request()->routeIs('payroll.*')],
                ['label' => 'گزارش مالی',   'href' => $safeRoute('reports.finance'), 'icon' => 'chart-bar',       'active' => request()->routeIs('reports.finance')],
            ],
        ],
        [
            'label'  => 'ترانسپورت',
            'icon'   => 'truck',
            'active' => request()->routeIs('transport.*'),
            'children' => [
                ['label' => 'موتر، راننده و مسیر', 'href' => $safeRoute('transport.index'),            'icon' => 'truck',        'active' => request()->routeIs('transport.index', 'transport.create', 'transport.show', 'transport.edit')],
                ['label' => 'ترانسپورت شاگردان',   'href' => $safeRoute('transport.assignments.index'), 'icon' => 'academic-cap', 'active' => request()->routeIs('transport.assignments.*')],
            ],
        ],
        [
            'label'  => 'فروش و انبار',
            'icon'   => 'rectangle-stack',
            'active' => request()->routeIs('sales.*'),
            'children' => [
                ['label' => 'آیتم‌های فروش',  'href' => $safeRoute('sales.items.index'), 'icon' => 'rectangle-stack', 'active' => request()->routeIs('sales.items.*')],
                ['label' => 'فروش شاگردان',    'href' => $safeRoute('sales.index'),       'icon' => 'banknotes',       'active' => request()->routeIs('sales.*') && ! request()->routeIs('sales.items.*')],
            ],
        ],
        [
            'label'  => 'کتابخانه',
            'icon'   => 'book-open',
            'active' => request()->routeIs('library.*', 'resources.*'),
            'children' => [
                ['label' => 'کتاب‌ها',   'href' => $safeRoute('library.books.index'),     'icon' => 'book-open',      'active' => request()->routeIs('library.books.*')],
                ['label' => 'امانت کتاب', 'href' => $safeRoute('library.loans.index'),     'icon' => 'arrow-path',     'active' => request()->routeIs('library.loans.*')],
                ['label' => 'مواد درسی', 'href' => $safeRoute('library.materials.index'), 'icon' => 'rectangle-stack', 'active' => request()->routeIs('library.materials.*')],
            ],
        ],
        [
            'label'  => 'گزارش‌ها',
            'icon'   => 'chart-bar',
            'active' => request()->routeIs('reports.*'),
            'children' => [
                ['label' => 'تاریخچه گزارش‌ها', 'href' => $safeRoute('reports.index'),      'icon' => 'chart-bar',          'active' => request()->routeIs('reports.index')],
                ['label' => 'گزارش شاگردان',    'href' => $safeRoute('reports.students'),    'icon' => 'academic-cap',       'active' => request()->routeIs('reports.students')],
                ['label' => 'گزارش حاضری',      'href' => $safeRoute('reports.attendance'),  'icon' => 'check',              'active' => request()->routeIs('reports.attendance')],
                ['label' => 'گزارش مالی',       'href' => $safeRoute('reports.finance'),     'icon' => 'banknotes',          'active' => request()->routeIs('reports.finance')],
                ['label' => 'گزارش امتحانات',   'href' => $safeRoute('reports.exams'),       'icon' => 'document-chart-bar', 'active' => request()->routeIs('reports.exams')],
            ],
        ],
        [
            'label'  => 'تنظیمات',
            'icon'   => 'cog-6-tooth',
            'active' => request()->routeIs('users.*', 'roles.*', 'permissions.*', 'settings.*'),
            'children' => [
                ['label' => 'کاربران',       'href' => $safeRoute('users.index'),       'icon' => 'users',       'active' => request()->routeIs('users.*')],
                ['label' => 'نقش‌ها',        'href' => $safeRoute('roles.index'),       'icon' => 'shield-check', 'active' => request()->routeIs('roles.*')],
                ['label' => 'صلاحیت‌ها',     'href' => $safeRoute('permissions.index'), 'icon' => 'lock-closed', 'active' => request()->routeIs('permissions.*')],
                ['label' => 'تنظیمات عمومی', 'href' => $safeRoute('settings.index'),   'icon' => 'cog-6-tooth', 'active' => request()->routeIs('settings.*')],
            ],
        ],
    ];
@endphp

@once
    @push('scripts')
        <script>
            document.addEventListener('keydown', e => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    document.getElementById('admin-global-search')?.focus();
                }
            });
        </script>
    @endpush
@endonce

{{-- ─── Shared nav macro ─── --}}
@php
    $navItems = $navigation;
@endphp

<div>

    {{-- ══════════════════════════════════════════
         MOBILE OVERLAY
    ══════════════════════════════════════════ --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity.duration.200ms
        x-on:click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:hidden"
    ></div>

    {{-- ══════════════════════════════════════════
         MOBILE SIDEBAR
    ══════════════════════════════════════════ --}}
    <aside
        x-cloak
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed inset-y-0 right-0 z-50 flex flex-col bg-white border-l shadow-2xl w-72 border-slate-200/60 dark:border-slate-800 dark:bg-slate-950 shadow-slate-950/20 lg:hidden"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-slate-100 dark:border-slate-800/80">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center min-w-0 gap-3">
                <span class="flex items-center justify-center text-white h-9 w-9 shrink-0 rounded-xl bg-slate-900 dark:bg-indigo-600">
                    <x-ui.icon name="sparkles" class="w-4 h-4" />
                </span>
                <span class="text-sm font-semibold truncate text-slate-800 dark:text-white">{{ $brand }}</span>
            </a>
            <button
                type="button"
                x-on:click="sidebarOpen = false"
                class="flex items-center justify-center w-8 h-8 transition rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
            >
                <x-ui.icon name="x-mark" class="w-4 h-4" />
            </button>
        </div>

        {{-- Nav --}}
        <div class="flex-1 px-3 py-3 overflow-y-auto ui-scrollbar">
            <nav class="space-y-0.5" aria-label="فهرست موبایل">
                @foreach ($navItems as $item)
                    @if (isset($item['children']))
                        @php($open = $item['active'] ?? false)
                        <div x-data="{ open: @js($open) }">
                            <button
                                type="button"
                                x-on:click="open = !open"
                                x-bind:aria-expanded="open"
                                class="group flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-right text-sm font-medium transition
                                       text-slate-600 hover:bg-slate-50 hover:text-slate-900
                                       dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white"
                                x-bind:class="open ? 'bg-slate-50 text-slate-900 dark:bg-slate-800/60 dark:text-white' : ''"
                            >
                                <x-ui.icon :name="$item['icon']" class="w-4 h-4 shrink-0" />
                                <span class="flex-1 truncate">{{ $item['label'] }}</span>
                                <x-ui.icon name="chevron-down" class="h-3.5 w-3.5 shrink-0 text-slate-400 transition"
                                    x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div
                                x-cloak x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="relative mt-0.5 space-y-0.5 pr-4
                                       before:absolute before:right-[1.1rem] before:top-1 before:bottom-1
                                       before:w-px before:bg-slate-200 dark:before:bg-slate-700"
                            >
                                @foreach ($item['children'] as $child)
                                    <a
                                        href="{{ $child['href'] }}"
                                        wire:navigate
                                        x-on:click="sidebarOpen = false"
                                        @class([
                                            'flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition',
                                            'bg-indigo-50 text-indigo-700 font-medium dark:bg-indigo-950/60 dark:text-indigo-400' => $child['active'] ?? false,
                                            'text-slate-500 hover:bg-slate-50 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white' => !($child['active'] ?? false),
                                        ])
                                    >
                                        <x-ui.icon :name="$child['icon']" class="h-3.5 w-3.5 shrink-0" />
                                        <span class="truncate">{{ $child['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a
                            href="{{ $item['href'] }}"
                            wire:navigate
                            x-on:click="sidebarOpen = false"
                            @class([
                                'flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400' => $item['active'] ?? false,
                                'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' => !($item['active'] ?? false),
                            ])
                        >
                            <x-ui.icon :name="$item['icon']" class="w-4 h-4 shrink-0" />
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>

        {{-- User --}}
        <div class="p-4 border-t border-slate-100 dark:border-slate-800/80">
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-800/60">
                <span class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-white rounded-full shrink-0 bg-gradient-to-br from-indigo-500 to-sky-500">
                    {{ $initials }}
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate text-slate-800 dark:text-white">{{ $userName }}</p>
                    <p class="text-xs truncate text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════
         DESKTOP SIDEBAR
    ══════════════════════════════════════════ --}}
    <aside class="admin-sidebar fixed inset-y-0 right-0 z-30 hidden flex-col
                  border-l border-slate-200/70 bg-white
                  dark:border-slate-800 dark:bg-slate-950
                  shadow-[0_0_40px_-12px_rgba(0,0,0,0.12)]
                  transition-[width] duration-300 ease-out
                  lg:flex">

        {{-- Logo --}}
        <div class="px-4 py-4 border-b border-slate-100 dark:border-slate-800/80">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center min-w-0 gap-3">
                <span class="flex items-center justify-center text-white h-9 w-9 shrink-0 rounded-xl bg-slate-900 dark:bg-indigo-600">
                    <x-ui.icon name="sparkles" class="w-4 h-4" />
                </span>
                <span class="text-sm font-semibold truncate admin-collapse-hide text-slate-800 dark:text-white">
                    {{ $brand }}
                </span>
            </a>
        </div>

        {{-- Collapse toggle --}}
        <div class="border-b border-slate-100 px-4 py-2.5 dark:border-slate-800/80">
            <button
                type="button"
                x-on:click="toggleSidebar()"
                class="flex items-center justify-center w-8 h-8 transition rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                title="بستن / باز کردن سایدبار"
            >
                <x-ui.icon name="bars-3" class="w-4 h-4" />
            </button>
        </div>

        {{-- Nav --}}
        <div class="flex-1 px-3 py-3 overflow-y-auto ui-scrollbar">
            <nav class="space-y-0.5" aria-label="فهرست اصلی">
                @foreach ($navItems as $item)
                    @if (isset($item['children']))
                        @php($open = $item['active'] ?? false)
                        <div x-data="{ open: @js($open) }">
                            <button
                                type="button"
                                x-on:click="open = !open; collapsed && toggleSidebar()"
                                x-bind:aria-expanded="open"
                                class="group flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-right text-sm font-medium transition
                                       text-slate-600 hover:bg-slate-50 hover:text-slate-900
                                       dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white"
                                x-bind:class="open ? 'bg-slate-50 text-slate-900 dark:bg-slate-800/60 dark:text-white' : ''"
                            >
                                <x-ui.icon :name="$item['icon']" class="w-4 h-4 shrink-0" />
                                <span class="flex-1 truncate admin-collapse-hide">{{ $item['label'] }}</span>
                                <x-ui.icon name="chevron-down"
                                    class="admin-collapse-hide h-3.5 w-3.5 shrink-0 text-slate-400 transition"
                                    x-bind:class="open ? 'rotate-180' : ''" />
                            </button>

                            <div
                                x-cloak x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="admin-collapse-hide relative mt-0.5 space-y-0.5 pr-4
                                       before:absolute before:right-[1.1rem] before:top-1 before:bottom-1
                                       before:w-px before:bg-slate-200 dark:before:bg-slate-700"
                            >
                                @foreach ($item['children'] as $child)
                                    <a
                                        href="{{ $child['href'] }}"
                                        wire:navigate
                                        @class([
                                            'flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition',
                                            'bg-indigo-50 text-indigo-700 font-medium dark:bg-indigo-950/60 dark:text-indigo-400' => $child['active'] ?? false,
                                            'text-slate-500 hover:bg-slate-50 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-white' => !($child['active'] ?? false),
                                        ])
                                    >
                                        <x-ui.icon :name="$child['icon']" class="h-3.5 w-3.5 shrink-0" />
                                        <span class="truncate">{{ $child['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a
                            href="{{ $item['href'] }}"
                            wire:navigate
                            @class([
                                'flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                                'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400' => $item['active'] ?? false,
                                'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/60 dark:hover:text-white' => !($item['active'] ?? false),
                            ])
                        >
                            <x-ui.icon :name="$item['icon']" class="w-4 h-4 shrink-0" />
                            <span class="truncate admin-collapse-hide">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>


    </aside>

</div>
