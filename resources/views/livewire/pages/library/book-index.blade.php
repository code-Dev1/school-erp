<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div><p class="text-sm font-medium text-slate-500 dark:text-slate-400">کتابخانه و منابع</p><h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">کتاب ها</h2></div>
        <x-ui.button href="{{ route('library.books.create') }}" icon="plus" wire:navigate>ثبت کتاب</x-ui.button>
    </section>
    @if (session('status')) <x-ui.alert variant="success" dismissible>{{ session('status') }}</x-ui.alert> @endif
    <section class="overflow-hidden rounded-2xl border border-white/70 bg-white shadow-xl shadow-slate-950/[0.04] dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto ui-scrollbar">
            <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900 dark:text-slate-300"><tr><th class="px-4 py-3 text-right font-semibold">کتاب</th><th class="px-4 py-3 text-right font-semibold">نویسنده</th><th class="px-4 py-3 text-right font-semibold">دسته</th><th class="px-4 py-3 text-right font-semibold">نسخه ها</th><th class="px-4 py-3 text-right font-semibold">وضعیت</th><th class="px-4 py-3 text-left font-semibold">عملیات</th></tr></thead>
                <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-950">
                    @forelse ($books as $book)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-slate-900/80">
                            <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-950 dark:text-white">{{ $book->title }}</td><td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $book->author ?: 'ثبت نشده' }}</td><td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $book->category ?: 'عمومی' }}</td><td class="whitespace-nowrap px-4 py-4 text-slate-700 dark:text-slate-200">{{ $book->available_copies }} / {{ $book->total_copies }}</td><td class="whitespace-nowrap px-4 py-4"><x-ui.badge variant="{{ $book->status === 'available' ? 'success' : 'neutral' }}">{{ $statusOptions[$book->status] ?? $book->status }}</x-ui.badge></td><td class="px-4 py-4"><div class="flex justify-end"><x-ui.button type="button" size="sm" variant="danger" icon="trash" wire:click="delete({{ $book->id }})" wire:confirm="این کتاب حذف شود؟">حذف</x-ui.button></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-slate-500 dark:text-slate-400">کتابی ثبت نشده است.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($books->hasPages()) <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-800">{{ $books->links() }}</div> @endif
    </section>
</div>
