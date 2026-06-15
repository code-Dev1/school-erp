<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <header class="flex items-start gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                <x-ui.icon name="exclamation-triangle" class="h-5 w-5" />
            </span>
            <div>
                <h3 class="text-base font-semibold text-slate-950 dark:text-white">حذف حساب</h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                    با حذف حساب، دسترسی و معلومات مربوط به این کاربر برای همیشه حذف می‌شود.
                </p>
            </div>
        </header>

        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex h-11 shrink-0 items-center justify-center rounded-2xl bg-rose-600 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2"
        >
            حذف حساب
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">
            <div class="flex items-start gap-3">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                    <x-ui.icon name="trash" class="h-5 w-5" />
                </span>
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">حساب حذف شود؟</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        برای تایید حذف حساب، رمز عبور خود را وارد کنید.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <label for="password" class="sr-only">رمز عبور</label>
                <div class="relative max-w-sm">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="lock-closed" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="password"
                        id="password"
                        name="password"
                        type="password"
                        dir="ltr"
                        placeholder="رمز عبور"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10"
                    >
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">
                    لغو
                </button>

                <button type="submit" class="inline-flex h-11 items-center justify-center rounded-2xl bg-rose-600 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2">
                    حذف نهایی
                </button>
            </div>
        </form>
    </x-modal>
</section>
