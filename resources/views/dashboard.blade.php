@php
    $breadcrumbs = [
        ['label' => 'داشبورد'],
    ];
@endphp

<x-layouts.app title="داشبورد" :breadcrumbs="$breadcrumbs">
    <div class="grid min-h-[60vh] place-items-center">
        <section class="w-full max-w-3xl rounded-2xl border border-white/70 bg-white/85 p-8 text-center shadow-xl shadow-slate-950/[0.05] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-950 text-white shadow-lg shadow-slate-950/15 dark:bg-white dark:text-slate-950">
                <x-ui.icon name="home" class="h-7 w-7" />
            </span>

            <h1 class="mt-5 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">داشبورد</h1>
            <p class="mt-3 text-sm leading-7 text-slate-500 dark:text-slate-400">
                فعلا فقط صفحه داشبورد فعال است.
            </p>
        </section>
    </div>
</x-layouts.app>
