@props([
    'title' => 'صفحه مدیریت',
])

@php
    $user = auth()->user();
    $userName = $user?->name ?? 'مدیر سیستم';
    $userEmail = $user?->email ?? 'admin@example.com';
    $initials = Illuminate\Support\Str::of($userName)->explode(' ')->map(fn ($part) => Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('');
@endphp

<header {{ $attributes->merge(['class' => 'sticky top-0 z-20 border-b border-white/30 bg-white/80 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/75']) }}>
    <div class="flex items-center justify-between h-16 gap-8 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            x-on:click="sidebarOpen = true"
            class="p-2 transition rounded-xl text-slate-500 hover:bg-white hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white lg:hidden"
        >
            <x-ui.icon name="bars-3" class="w-5 h-5" />
            <span class="sr-only">باز کردن نوار کناری</span>
        </button>

        <div class="flex-1 min-w-0">

            <h1 class="text-lg font-semibold truncate text-slate-950 dark:text-white">{{ $title }}</h1>
        </div>

        <div class="justify-center flex-1 hidden md:flex">
            <label for="admin-global-search" class="relative w-full max-w-md">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <x-ui.icon name="magnifying-glass" class="w-4 h-4" />
                </span>
                <input
                    id="admin-global-search"
                    type="search"
                    placeholder="جستجوی محصل، استاد، اتاق یا گزارش..."
                    class="w-full h-10 pl-20 pr-10 text-sm transition border shadow-sm outline-none rounded-2xl border-slate-200 bg-white/80 text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900/80 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                >
                <span class="absolute inset-y-0 left-0 items-center hidden pl-3 text-xs font-medium pointer-events-none text-slate-400 xl:flex">کنترول K</span>
            </label>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                x-on:click="toggleDarkMode()"
                class="p-2 transition border shadow-sm rounded-xl border-slate-200 bg-white/80 text-slate-500 hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:text-white"
            >
                <x-ui.icon name="moon" class="hidden w-5 h-5 dark:block" />
                <x-ui.icon name="sun" class="w-5 h-5 dark:hidden" />
                <span class="sr-only">تغییر حالت تاریک</span>
            </button>

            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    x-on:click="open = ! open"
                    x-on:keydown.escape.window="open = false"
                    class="relative p-2 transition border shadow-sm rounded-xl border-slate-200 bg-white/80 text-slate-500 hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:text-white"
                    x-bind:aria-expanded="open"
                    aria-haspopup="menu"
                >
                    <x-ui.icon name="bell" class="w-5 h-5" />
                    <span class="absolute w-2 h-2 rounded-full left-2 top-2 bg-rose-500 ring-2 ring-white dark:ring-slate-900"></span>
                    <span class="sr-only">اطلاعیه‌ها</span>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.left
                    x-on:click.outside="open = false"
                    class="absolute left-0 mt-3 overflow-hidden bg-white border shadow-2xl w-80 rounded-2xl border-slate-200 shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-950"
                >
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                        <p class="text-sm font-semibold text-slate-950 dark:text-white">اطلاعیه‌ها</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">۳ پیام تازه برای بررسی دارید.</p>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <a href="#" class="block p-4 transition hover:bg-slate-50 dark:hover:bg-slate-900">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">پرداخت فیس ثبت شد</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">پرداخت ماهانه یکی از محصلین موفقانه ثبت گردید.</p>
                        </a>

                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    x-on:click="open = ! open"
                    x-on:keydown.escape.window="open = false"
                    class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white/80 p-1.5 pl-3 shadow-sm transition hover:border-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-slate-700"
                    x-bind:aria-expanded="open"
                    aria-haspopup="menu"
                >
                    <span class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-white rounded-full bg-gradient-to-br from-indigo-600 to-sky-500">
                        {{ $initials }}
                    </span>
                    <span class="hidden text-sm font-medium truncate max-w-32 text-slate-700 dark:text-slate-200 sm:block">{{ $userName }}</span>
                    <x-ui.icon name="chevron-down" class="hidden w-4 h-4 text-slate-400 sm:block" />
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.left
                    x-on:click.outside="open = false"
                    class="absolute left-0 w-64 mt-3 overflow-hidden bg-white border shadow-2xl rounded-2xl border-slate-200 shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-950"
                    role="menu"
                >
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                        <p class="text-sm font-semibold truncate text-slate-950 dark:text-white">{{ $userName }}</p>
                        <p class="text-xs truncate text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                    </div>
                    <div class="p-1">
                        <a href="{{ url('/profile') }}" class="flex items-center gap-2 px-3 py-2 text-sm font-medium transition rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white" role="menuitem">
                            <x-ui.icon name="cog-6-tooth" class="w-4 h-4" />
                            تنظیمات حساب
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full gap-2 px-3 py-2 text-sm font-medium text-right transition rounded-xl text-slate-600 hover:bg-slate-100 hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/40 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white" role="menuitem">
                                <x-ui.icon name="arrow-left-on-rectangle" class="w-4 h-4" />
                                خروج از حساب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
