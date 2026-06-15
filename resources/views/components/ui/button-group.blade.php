@props([
    'label' => 'Button group',
])

<div
    role="group"
    aria-label="{{ $label }}"
    {{ $attributes->merge(['class' => 'inline-flex overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 [&>*]:rounded-none [&>*]:border-0 [&>*+*]:border-s [&>*+*]:border-slate-200 dark:[&>*+*]:border-slate-700']) }}
>
    {{ $slot }}
</div>
