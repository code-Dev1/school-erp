<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">فروشات و موجودی</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">ایجاد فروش</h2>
        </div>

        <x-ui.button
            variant="secondary"
            href="{{ route('sales.index') }}"
            icon="chevron-right"
            wire:navigate
        >
            برگشت
        </x-ui.button>
    </section>

    @if ($errors->any())
        <x-ui.alert variant="error" title="لطفاً فورم را بررسی کنید.">
            برخی از فیلدها نیاز به اصلاح دارند.
        </x-ui.alert>
    @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معلومات فروش" icon="shopping-bag">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

                <x-ui.input
                    label="نمبر فاکتور"
                    name="form.invoice_number"
                    wire:model="form.invoice_number"
                />

                <x-ui.select
                    label="شاگرد"
                    name="form.student_id"
                    :options="$studentOptions"
                    placeholder="اختیاری"
                    wire:model="form.student_id"
                />

                <x-ui.input
                    type="date"
                    label="تاریخ فروش"
                    name="form.sold_at"
                    wire:model="form.sold_at"
                />

                <x-ui.select
                    label="قلم"
                    name="form.sale_item_id"
                    :options="$itemOptions"
                    placeholder="قلم را انتخاب کنید"
                    wire:model="form.sale_item_id"
                />

                <x-ui.input
                    type="number"
                    label="تعداد"
                    name="form.quantity"
                    min="1"
                    wire:model="form.quantity"
                />

                <x-ui.input
                    type="number"
                    label="تخفیف"
                    name="form.discount_amount"
                    min="0"
                    step="0.01"
                    wire:model="form.discount_amount"
                />

                <x-ui.input
                    type="number"
                    label="مبلغ پرداخت‌شده"
                    name="form.paid_amount"
                    min="0"
                    step="0.01"
                    wire:model="form.paid_amount"
                />

                <x-ui.select
                    label="وضعیت"
                    name="form.status"
                    :options="$statusOptions"
                    wire:model="form.status"
                />

                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea
                        label="یادداشت"
                        name="form.note"
                        rows="3"
                        wire:model="form.note"
                    />
                </div>

            </div>
        </x-ui.card>

        <div class="flex justify-end gap-3">
            <x-ui.button
                variant="secondary"
                href="{{ route('sales.index') }}"
                wire:navigate
            >
                انصراف
            </x-ui.button>

            <x-ui.button type="submit" icon="check">
                ذخیره
            </x-ui.button>
        </div>
    </form>
</div>
