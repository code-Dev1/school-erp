@props([
    'title' => 'داشبورد',
])

@php
    $user = auth()->user();
    $userName = $user?->name ?? 'مدیر سیستم';
    $userEmail = $user?->email ?? 'admin@example.com';
    $initials = Illuminate\Support\Str::of($userName)->explode(' ')->map(fn ($part) => Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('');
@endphp

<header {{ $attributes->merge(['class' => 'sticky top-0 z-20 border-b border-white/30 bg-white/80 backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/75']) }}>
    <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            x-on:click="sidebarOpen = true"
            class="rounded-xl p-2 text-slate-500 transition hover:bg-white hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/50 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white lg:hidden"
        >
            <x-ui.icon name="bars-3" class="h-5 w-5" />
            <span class="sr-only">باز کردن نوار کناری</span>
        </button>

        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">پنل مدیریت</p>
            <h1 class="truncate text-lg font-semibold text-slate-950 dark:text-white">{{ $title }}</h1>
        </div>

        <div class="hidden flex-1 justify-center md:flex">
            <label for="admin-global-search" class="relative w-full max-w-md">
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                    <x-ui.icon name="magnifying-glass" class="h-4 w-4" />
                </span>
                <input
                    id="admin-global-search"
                    type="search"
                    placeholder="جستجوی محصل، استاد، اتاق یا گزارش..."
                    class="h-10 w-full rounded-2xl border border-slate-200 bg-white/80 pr-10 pl-20 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900/80 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                >
                <span class="pointer-events-none absolute inset-y-0 left-0 hidden items-center pl-3 text-xs font-medium text-slate-400 xl:flex">کنترول K</span>
            </label>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                x-on:click="toggleDarkMode()"
                class="rounded-xl border border-slate-200 bg-white/80 p-2 text-slate-500 shadow-sm transition hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:text-white"
            >
                <x-ui.icon name="moon" class="hidden h-5 w-5 dark:block" />
                <x-ui.icon name="sun" class="h-5 w-5 dark:hidden" />
                <span class="sr-only">تغییر حالت تاریک</span>
            </button>

            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    x-on:click="open = ! open"
                    x-on:keydown.escape.window="open = false"
                    class="relative rounded-xl border border-slate-200 bg-white/80 p-2 text-slate-500 shadow-sm transition hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-300 dark:hover:text-white"
                    x-bind:aria-expanded="open"
                    aria-haspopup="menu"
                >
                    <x-ui.icon name="bell" class="h-5 w-5" />
                    <span class="absolute left-2 top-2 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900"></span>
                    <span class="sr-only">اطلاعیه‌ها</span>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.left
                    x-on:click.outside="open = false"
                    class="absolute left-0 mt-3 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-950"
                >
                    <div class="border-b border-slate-100 p-4 dark:border-slate-800">
                        <p class="text-sm font-semibold text-slate-950 dark:text-white">اطلاعیه‌ها</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">۳ پیام تازه برای بررسی دارید.</p>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <a href="#" class="block p-4 transition hover:bg-slate-50 dark:hover:bg-slate-900">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">پرداخت فیس ثبت شد</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">پرداخت ماهانه یکی از محصلین موفقانه ثبت گردید.</p>
                        </a>
                        <a href="#" class="block p-4 transition hover:bg-slate-50 dark:hover:bg-slate-900">
                            <p class="text-sm font-medium text-slate-900 dark:text-white">اتاق جدید اضافه شد</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400">اتاق ۲۰۴ برای لیلیه فعال گردید.</p>
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
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-600 to-sky-500 text-xs font-semibold text-white">
                        {{ $initials }}
                    </span>
                    <span class="hidden max-w-32 truncate text-sm font-medium text-slate-700 dark:text-slate-200 sm:block">{{ $userName }}</span>
                    <x-ui.icon name="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block" />
                </button>

                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.left
                    x-on:click.outside="open = false"
                    class="absolute left-0 mt-3 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-950"
                    role="menu"
                >
                    <div class="border-b border-slate-100 p-4 dark:border-slate-800">
                        <p class="truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $userName }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                    </div>
                    <div class="p-1">
                        <a href="{{ url('/profile') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white" role="menuitem">
                            <x-ui.icon name="cog-6-tooth" class="h-4 w-4" />
                            تنظیمات حساب
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-right text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500/40 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white" role="menuitem">
                                <x-ui.icon name="arrow-left-on-rectangle" class="h-4 w-4" />
                                خروج از حساب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
