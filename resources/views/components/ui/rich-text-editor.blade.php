@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'value' => '',
    'hint' => null,
    'error' => null,
])

@php
    $id = $id ?: ($name ?: 'rich-text-'.Illuminate\Support\Str::random(8));
    $errorBag = $errors ?? new Illuminate\Support\ViewErrorBag;
    $errorText = $error ?: ($name && $errorBag->has($name) ? $errorBag->first($name) : null);
@endphp

<div
    x-data="uiRichText({ value: @js($value) })"
    {{ $attributes->whereDoesntStartWith('wire:model')->except(['class'])->merge(['class' => 'w-full']) }}
>
    @if ($label)
        <label for="{{ $id }}" class="ui-label">{{ $label }}</label>
    @endif

    <div class="overflow-hidden rounded-lg border border-slate-300 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-950">
        <div class="flex flex-wrap gap-1 border-b border-slate-200 bg-slate-50 p-2 dark:border-slate-800 dark:bg-slate-900">
            <x-ui.icon-button icon="pencil-square" label="Bold" size="sm" x-on:click.prevent="command('bold')" />
            <x-ui.icon-button icon="sparkles" label="Italic" size="sm" x-on:click.prevent="command('italic')" />
            <x-ui.icon-button icon="bars-3" label="Bulleted list" size="sm" x-on:click.prevent="command('insertUnorderedList')" />
            <x-ui.icon-button icon="link" label="Link" size="sm" x-on:click.prevent="command('createLink', prompt('URL'))" />
            <x-ui.icon-button icon="x-mark" label="Clear" size="sm" x-on:click.prevent="$refs.editor.innerHTML = ''; sync()" />
        </div>

        <input
            x-ref="input"
            type="hidden"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            {{ $attributes->whereStartsWith('wire:model') }}
        >

        <div
            x-ref="editor"
            x-html="value"
            contenteditable="true"
            role="textbox"
            aria-multiline="true"
            x-on:input="sync"
            class="min-h-[12rem] px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition focus:bg-slate-50 dark:text-slate-100 dark:focus:bg-slate-900"
        ></div>
    </div>

    @if ($hint && ! $errorText)
        <p class="ui-help">{{ $hint }}</p>
    @endif

    @if ($errorText)
        <p class="ui-error">{{ $errorText }}</p>
    @endif
</div>
