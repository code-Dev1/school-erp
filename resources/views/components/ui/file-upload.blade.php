@props([
    'label' => 'Upload files',
    'name' => 'files',
    'multiple' => false,
    'accept' => '',
    'imagePreview' => false,
    'hint' => 'Drag files here or click to browse.',
])

<div x-data="uiFileUpload({ multiple: @js($multiple), accept: @js($accept) })" class="w-full">
    <label class="ui-label">{{ $label }}</label>
    <div
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        x-on:drop.prevent="dragging = false; handle($event.dataTransfer.files)"
        x-on:click="$refs.input.click()"
        x-bind:class="dragging ? 'border-indigo-400 bg-indigo-50 dark:border-sky-400 dark:bg-sky-400/10' : 'border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-950'"
        class="cursor-pointer rounded-lg border-2 border-dashed p-6 text-center transition"
    >
        <input x-ref="input" type="file" name="{{ $multiple ? $name.'[]' : $name }}" class="sr-only" @if ($multiple) multiple @endif accept="{{ $accept }}" x-on:change="handle($event.target.files)" {{ $attributes->whereStartsWith('wire:model') }}>
        <x-ui.icon name="cloud-arrow-up" class="mx-auto h-10 w-10 text-slate-400" />
        <p class="mt-3 text-sm font-medium text-slate-800 dark:text-slate-100">{{ $hint }}</p>
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400" x-text="files.length ? `${files.length} file(s) selected` : 'No files selected'"></p>
    </div>

    <div x-show="files.length" x-cloak class="mt-4 space-y-3">
        <template x-for="file in files" :key="file.name">
            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-950">
                @if ($imagePreview)
                    <img x-show="preview(file)" x-bind:src="preview(file)" alt="" class="h-12 w-12 rounded-md object-cover">
                @endif
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-slate-800 dark:text-slate-100" x-text="file.name"></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400" x-text="`${Math.round(file.size / 1024)} KB`"></p>
                </div>
                <x-ui.badge variant="success">Ready</x-ui.badge>
            </div>
        </template>
        <div>
            <div class="mb-2 flex items-center justify-between text-sm">
                <span class="font-medium text-slate-700 dark:text-slate-200">Upload progress</span>
                <span class="text-slate-500 dark:text-slate-400" x-text="`${progress}%`"></span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full rounded-full bg-gradient-to-r from-slate-950 via-indigo-600 to-sky-500 transition-all duration-300 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-300" x-bind:style="`width: ${progress}%`"></div>
            </div>
        </div>
    </div>
</div>
