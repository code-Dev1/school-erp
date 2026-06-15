@props([
    'lines' => 3,
    'avatar' => false,
])

<div {{ $attributes->merge(['class' => 'animate-pulse']) }}>
    <div class="flex gap-3">
        @if ($avatar)
            <div class="h-10 w-10 rounded-full bg-slate-200 dark:bg-slate-800"></div>
        @endif
        <div class="flex-1 space-y-3">
            @for ($line = 0; $line < $lines; $line++)
                <div class="h-3 rounded-full bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 animate-ui-shimmer dark:from-slate-800 dark:via-slate-700 dark:to-slate-800" style="width: {{ 100 - ($line * 12) }}%"></div>
            @endfor
        </div>
    </div>
</div>
