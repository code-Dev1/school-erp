@php
    $user = auth()->user();
    $userName = $user?->name ?? 'مدیر سیستم';
    $userEmail = $user?->email ?? 'admin@example.com';
    $initials = Illuminate\Support\Str::of($userName)->explode(' ')->map(fn ($part) => Illuminate\Support\Str::substr($part, 0, 1))->take(2)->implode('');

    $breadcrumbs = [
        ['label' => 'مدیریت', 'url' => url('/dashboard')],
        ['label' => 'تنظیمات حساب'],
    ];
@endphp

<x-layouts.app title="تنظیمات حساب" :breadcrumbs="$breadcrumbs">
    <x-slot name="header">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">حساب کاربری</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-normal text-slate-950 dark:text-white">تنظیمات حساب</h2>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
            معلومات شخصی، ایمیل ورود و رمز عبور خود را از همین بخش مدیریت کنید.
        </p>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[0.8fr_1.4fr]">
        <aside class="space-y-6">
            <section class="rounded-3xl border border-white/60 bg-white/80 p-6 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/65">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl bg-slate-950 text-xl font-bold text-white shadow-sm dark:bg-white dark:text-slate-950">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-lg font-semibold text-slate-950 dark:text-white">{{ $userName }}</h3>
                        <p class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400">{{ $userEmail }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-3">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-100 px-4 py-3 dark:bg-slate-900">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="shield-check" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-200">وضعیت حساب</span>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">فعال</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-slate-100 px-4 py-3 dark:bg-slate-900">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="lock-closed" class="h-5 w-5 text-indigo-600 dark:text-sky-400" />
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-200">امنیت ورود</span>
                        </div>
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">رمز محفوظ</span>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-950 shadow-sm dark:border-amber-500/25 dark:bg-amber-500/10 dark:text-amber-100">
                <div class="flex gap-3">
                    <x-ui.icon name="information-circle" class="mt-0.5 h-5 w-5 shrink-0" />
                    <p class="text-sm leading-6">
                        برای امنیت بهتر، بعد از تغییر ایمیل یا رمز عبور دوباره وارد سیستم شوید.
                    </p>
                </div>
            </section>
        </aside>

        <div class="space-y-6">
            <section class="rounded-3xl border border-white/60 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/65 sm:p-6">
                <livewire:profile.update-profile-information-form />
            </section>

            <section class="rounded-3xl border border-white/60 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-950/65 sm:p-6">
                <livewire:profile.update-password-form />
            </section>

            <section class="rounded-3xl border border-rose-200 bg-white/85 p-5 shadow-xl shadow-rose-950/[0.03] backdrop-blur-xl dark:border-rose-500/25 dark:bg-slate-950/65 sm:p-6">
                <livewire:profile.delete-user-form />
            </section>
        </div>
    </div>
</x-layouts.app>
