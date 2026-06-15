<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header class="flex items-start gap-3">
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
            <x-ui.icon name="user" class="h-5 w-5" />
        </span>
        <div>
            <h3 class="text-base font-semibold text-slate-950 dark:text-white">معلومات حساب</h3>
            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">نام و ایمیل ورود خود را به‌روز کنید.</p>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-5">
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">نام کامل</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="user" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="name"
                        id="name"
                        name="name"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                    >
                </div>
                <x-input-error class="mt-2 text-sm text-rose-600 dark:text-rose-400" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">ایمیل</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                        <x-ui.icon name="envelope" class="h-5 w-5" />
                    </span>
                    <input
                        wire:model="email"
                        id="email"
                        name="email"
                        type="email"
                        dir="ltr"
                        required
                        autocomplete="username"
                        class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-left text-sm text-slate-950 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-sky-400 dark:focus:ring-sky-400/10"
                    >
                </div>
                <x-input-error class="mt-2 text-sm text-rose-600 dark:text-rose-400" :messages="$errors->get('email')" />
            </div>
        </div>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-800 dark:border-amber-500/25 dark:bg-amber-500/10 dark:text-amber-100">
                ایمیل شما هنوز تایید نشده است.
                <button wire:click.prevent="sendVerification" class="font-semibold text-amber-950 underline underline-offset-4 dark:text-amber-50">
                    ارسال دوباره لینک تایید
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-semibold text-emerald-700 dark:text-emerald-300">لینک تایید تازه به ایمیل شما فرستاده شد.</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-4">
            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-950 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                ذخیره تغییرات
            </button>

            <x-action-message class="text-sm font-semibold text-emerald-600 dark:text-emerald-400" on="profile-updated">
                ذخیره شد.
            </x-action-message>
        </div>
    </form>
</section>
