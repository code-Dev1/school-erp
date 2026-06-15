<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'سامانه آموزشی') }}</title>

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        @php
            $isLoginPage = request()->path() === '/' || request()->routeIs('login');
        @endphp

        @if ($isLoginPage)
            {{ $slot }}
        @else
            <main class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-10">
                <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-950/5">
                    <div class="mb-6 flex justify-center">
                        <a href="/" wire:navigate class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-950 text-white">
                            <x-ui.icon name="academic-cap" class="h-7 w-7" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </main>
        @endif

        @livewireScripts
    </body>
</html>
