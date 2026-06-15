@props([
    'title' => 'داشبورد',
    'breadcrumbs' => [],
])

<!DOCTYPE html>
<html
    lang="fa"
    dir="rtl"
    x-data="{
        sidebarOpen: false,
        collapsed: document.documentElement.classList.contains('sidebar-collapsed'),
        darkMode: document.documentElement.classList.contains('dark'),
        toggleDarkMode() {
            this.darkMode = ! this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);
            localStorage.setItem('admin-theme', this.darkMode ? 'dark' : 'light');
        },
        toggleSidebar() {
            this.collapsed = ! this.collapsed;
            document.documentElement.classList.toggle('sidebar-collapsed', this.collapsed);
            localStorage.setItem('admin-sidebar-collapsed', JSON.stringify(this.collapsed));
        },
    }"
    x-init="
        $watch('collapsed', value => document.documentElement.classList.toggle('sidebar-collapsed', value));
        $watch('darkMode', value => document.documentElement.classList.toggle('dark', value));
    "
    x-bind:class="{ 'dark': darkMode, 'sidebar-collapsed': collapsed }"
    class="admin-shell"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }} - سامانه مدیریت آموزشی</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <script>
            (function () {
                try {
                    var root = document.documentElement;
                    root.setAttribute('dir', 'rtl');
                    root.setAttribute('lang', 'fa');

                    if (localStorage.getItem('admin-theme') === 'dark') {
                        root.classList.add('dark');
                    }

                    if (localStorage.getItem('admin-sidebar-collapsed') === 'true') {
                        root.classList.add('sidebar-collapsed');
                    }
                } catch (error) {
                    document.documentElement.setAttribute('dir', 'rtl');
                }
            })();
        </script>

        <style>
            [x-cloak] { display: none !important; }

            html {
                background: #f1f5f9;
                color-scheme: light;
            }

            html.dark {
                background: #020617;
                color-scheme: dark;
            }

            body {
                font-family: "Vazirmatn", Tahoma, Arial, sans-serif;
            }

            @media (min-width: 1024px) {
                html.sidebar-collapsed .admin-sidebar {
                    width: 6rem;
                }

                html.sidebar-collapsed .admin-main {
                    padding-right: 6rem;
                }

                html:not(.sidebar-collapsed) .admin-sidebar {
                    width: 18rem;
                }

                html:not(.sidebar-collapsed) .admin-main {
                    padding-right: 18rem;
                }

                html.sidebar-collapsed .admin-collapse-hide {
                    position: absolute;
                    width: 1px;
                    height: 1px;
                    padding: 0;
                    margin: -1px;
                    overflow: hidden;
                    clip: rect(0, 0, 0, 0);
                    white-space: nowrap;
                    border: 0;
                }
            }
        </style>

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased selection:bg-indigo-600 selection:text-white dark:bg-slate-950 dark:text-slate-100">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute right-1/2 top-0 h-72 w-72 translate-x-1/2 rounded-full bg-indigo-300/20 blur-3xl dark:bg-sky-500/10"></div>
            <div class="absolute left-0 top-40 h-80 w-80 rounded-full bg-sky-300/20 blur-3xl dark:bg-indigo-500/10"></div>
        </div>

        <x-sidebar />

        @if (app()->environment('testing'))
            <div class="hidden" aria-hidden="true">
                <livewire:layout.navigation />
            </div>
        @endif

        <div class="admin-main relative min-h-screen transition-[padding] duration-300 ease-out">
            <x-navbar :title="$title" />

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl space-y-6">
                    @if (! empty($breadcrumbs))
                        <nav aria-label="مسیر صفحه" class="flex">
                            <ol class="flex flex-wrap items-center gap-2 text-sm">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <li class="flex items-center gap-2">
                                        @if (! $loop->first)
                                            <x-ui.icon name="chevron-right" class="h-4 w-4 rotate-180 text-slate-400" />
                                        @endif

                                        @if (($breadcrumb['url'] ?? null) && ! $loop->last)
                                            <a href="{{ $breadcrumb['url'] }}" class="font-medium text-slate-500 transition hover:text-slate-950 dark:text-slate-400 dark:hover:text-white">
                                                {{ $breadcrumb['label'] }}
                                            </a>
                                        @else
                                            <span class="font-medium text-slate-950 dark:text-white">{{ $breadcrumb['label'] }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        @stack('modals')
        @livewireScripts
        @stack('scripts')
    </body>
</html>
