<main class="min-h-screen bg-slate-100 text-slate-950">
    <section class="flex items-center justify-center min-h-screen px-4 py-10 sm:px-6">
        <div class="w-full max-w-md">
            <div class="flex flex-col items-center text-center mb-7">
                <span class="flex items-center justify-center text-white shadow-lg h-14 w-14 rounded-2xl bg-slate-950 shadow-slate-950/20">
                    <x-ui.icon name="academic-cap" class="h-7 w-7" />
                </span>
                <div class="mt-4">
                    <p class="text-base font-bold text-slate-950">سیستم مدیریت مکتب وسعت دانش</p>
                    <p class="mt-1 text-sm text-slate-500">ورود امن به صفحه مدیریت مکتب</p>
                </div>
            </div>

            <div class="p-6 border shadow-2xl rounded-2xl border-white/80 bg-white/95 shadow-slate-950/10 backdrop-blur sm:p-8">
                <div class="text-center mb-7">
                    <p class="text-sm font-medium text-slate-500">خوش آمدید</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-normal text-slate-950">ورود به حساب</h2>
                </div>

                <x-auth-session-status class="px-4 py-3 mb-5 text-sm font-medium border rounded-2xl border-emerald-200 bg-emerald-50 text-emerald-700" :status="session('status')" />

                <form wire:submit="login" class="space-y-5">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-semibold text-slate-700">ایمیل</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                <x-ui.icon name="envelope" class="w-5 h-5" />
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
                                class="w-full h-12 pl-4 text-sm text-left transition border shadow-sm outline-none rounded-2xl border-slate-200 bg-slate-50 pr-11 text-slate-950 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-sm text-rose-600" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">رمز عبور</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-500">
                                    فراموش شده؟
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                <x-ui.icon name="lock-closed" class="w-5 h-5" />
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
                                class="w-full h-12 pl-4 text-sm text-left transition border shadow-sm outline-none rounded-2xl border-slate-200 bg-slate-50 pr-11 text-slate-950 placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-sm text-rose-600" />
                    </div>

                    <label for="remember" class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input wire:model="form.remember" id="remember" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded shadow-sm border-slate-300 focus:ring-indigo-500">
                        مرا به خاطر بسپار
                    </label>

                    <button type="submit" class="flex items-center justify-center w-full h-12 gap-2 px-5 text-sm font-bold text-white transition shadow-lg rounded-2xl bg-slate-950 shadow-slate-950/15 hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                        <x-ui.icon name="arrow-left-on-rectangle" class="w-5 h-5" />
                        ورود به سیستم
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
