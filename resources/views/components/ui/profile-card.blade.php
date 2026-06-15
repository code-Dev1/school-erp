@props([
    'name',
    'title' => null,
    'bio' => null,
    'avatar' => null,
    'cover' => null,
])

<x-ui.card padding="p-0" {{ $attributes }}>
    <div class="h-28 rounded-t-lg bg-gradient-to-r from-slate-950 via-indigo-700 to-sky-500 dark:from-slate-900 dark:via-slate-700 dark:to-sky-700">
        @if ($cover)
            <img src="{{ $cover }}" alt="" class="h-full w-full rounded-t-lg object-cover">
        @endif
    </div>

    <div class="px-5 pb-5">
        <div class="-mt-8 flex items-end justify-between gap-4">
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $name }}" class="h-16 w-16 rounded-full border-4 border-white object-cover dark:border-slate-950">
            @else
                <div class="flex h-16 w-16 items-center justify-center rounded-full border-4 border-white bg-slate-100 text-xl font-semibold text-slate-700 dark:border-slate-950 dark:bg-slate-800 dark:text-slate-200">
                    {{ Illuminate\Support\Str::of($name)->substr(0, 1)->upper() }}
                </div>
            @endif
            {{ $actions ?? '' }}
        </div>

        <h3 class="mt-4 text-base font-semibold text-slate-950 dark:text-white">{{ $name }}</h3>
        @if ($title)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $title }}</p>
        @endif
        @if ($bio)
            <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $bio }}</p>
        @endif
    </div>
</x-ui.card>
