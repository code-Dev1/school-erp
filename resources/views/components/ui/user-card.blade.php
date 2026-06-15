@props([
    'name',
    'email' => null,
    'role' => null,
    'status' => null,
    'avatar' => null,
])

<x-ui.card {{ $attributes }}>
    <div class="flex items-center gap-4">
        @if ($avatar)
            <img src="{{ $avatar }}" alt="{{ $name }}" class="h-12 w-12 rounded-full object-cover">
        @else
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                {{ Illuminate\Support\Str::of($name)->substr(0, 1)->upper() }}
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $name }}</p>
            @if ($email)
                <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ $email }}</p>
            @endif
            <div class="mt-2 flex flex-wrap gap-2">
                @if ($role)
                    <x-ui.badge variant="role">{{ $role }}</x-ui.badge>
                @endif
                @if ($status)
                    <x-ui.badge :variant="$status === 'active' ? 'success' : 'neutral'">{{ $status }}</x-ui.badge>
                @endif
            </div>
        </div>
    </div>
</x-ui.card>
