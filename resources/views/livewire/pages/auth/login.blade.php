<main class="min-h-screen bg-slate-100 text-slate-950">
    <section class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <div class="w-full max-w-md">
            <div class="mb-7 flex flex-col items-center text-center">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/20">
                    <x-ui.icon name="academic-cap" class="h-7 w-7" />
                </span>
                <div class="mt-4">
                    <p class="text-base font-bold text-slate-950">سامانه آموزشی</p>
                    <p class="mt-1 text-sm text-slate-500">ورود امن به پنل مدیریت مکتب</p>
                </div>
            </div>

            <div class="rounded-2xl border border-white/80 bg-white/95 p-6 shadow-2xl shadow-slate-950/10 backdrop-blur sm:p-8">
                <div class="mb-7 text-center">
                    <p class="text-sm font-medium text-slate-500">خوش آمدید</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-normal text-slate-950">ورود به حساب</h2>
                </div>

                <x-auth-session-status class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

                <form wire:submit="login" class="space-y-5">
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">ایمیل</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <x-ui.icon name="envelope" class="h-5 w-5" />
                            </span>
                            <input
                                wire:model="form.email"
                                id="email"
                                type="email"
                                name="email"
                                dir="ltr"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@example.com"
                                class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-sm text-rose-600" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="block text-sm font-semibold text-slate-700">رمز عبور</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-500">
                                    فراموش شده؟
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <x-ui.icon name="lock-closed" class="h-5 w-5" />
                            </span>
                            <input
                                wire:model="form.password"
                                id="password"
                                type="password"
                                name="password"
                                dir="ltr"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-sm text-rose-600" />
                    </div>

                    <label for="remember" class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input wire:model="form.remember" id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        مرا به خاطر بسپار
                    </label>

                    <button type="submit" class="flex h-12 w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 text-sm font-bold text-white shadow-lg shadow-slate-950/15 transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                        <x-ui.icon name="arrow-left-on-rectangle" class="h-5 w-5" />
                        ورود به سیستم
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
