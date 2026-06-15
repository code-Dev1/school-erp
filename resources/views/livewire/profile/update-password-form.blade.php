<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section>
    <header class="flex items-start gap-3">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 dark:bg-sky-500/10 dark:text-sky-300">
            <x-ui.icon name="lock-closed" class="h-5 w-5" />
        </span>
        <div>
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">تغییر رمز عبور</h3>
            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">برای امنیت حساب، رمز قوی و تازه انتخاب کنید.</p>
        </div>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-5">
        <div class="grid gap-5 lg:grid-cols-3">
            <div>
                <label for="update_password_current_password" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">رمز فعلی</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="lock-closed" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="current_password"
                        id="update_password_current_password"
                        name="current_password"
                        type="password"
                        dir="ltr"
                        autocomplete="current-password"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                    >
                </div>
                <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-sm text-rose-600 dark:text-rose-400" />
            </div>

            <div>
                <label for="update_password_password" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">رمز جدید</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="shield-check" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="password"
                        id="update_password_password"
                        name="password"
                        type="password"
                        dir="ltr"
                        autocomplete="new-password"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                    >
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600 dark:text-rose-400" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">تکرار رمز</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="check" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="password_confirmation"
                        id="update_password_password_confirmation"
                        name="password_confirmation"
                        type="password"
                        dir="ltr"
                        autocomplete="new-password"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                    >
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600 dark:text-rose-400" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                تغییر رمز
            </button>

            <x-action-message class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" on="password-updated">
                رمز عبور تغییر کرد.
            </x-action-message>
        </div>
    </form>
</section>
