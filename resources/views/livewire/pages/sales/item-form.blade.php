<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">فروشات و موجودی</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>

        <x-ui.button
            variant="secondary"
            href="{{ route('sales.items.index') }}"
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
        <x-ui.card title="معلومات قلم" icon="rectangle-stack">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

                <x-ui.input
                    label="کد کالا"
                    name="form.sku"
                    wire:model="form.sku"
                />

                <x-ui.input
                    label="نام"
                    name="form.name"
                    wire:model="form.name"
                />

                <x-ui.select
                    label="دسته‌بندی"
                    name="form.category"
                    :options="$categoryOptions"
                    wire:model="form.category"
                />

                <x-ui.input
                    type="number"
                    label="قیمت فی واحد"
                    name="form.unit_price"
                    min="0"
                    step="0.01"
                    wire:model="form.unit_price"
                />

                <x-ui.input
                    type="number"
                    label="مقدار موجود"
                    name="form.stock_quantity"
                    min="0"
                    wire:model="form.stock_quantity"
                />

                <x-ui.input
                    type="number"
                    label="سطح تجدید موجودی"
                    name="form.reorder_level"
                    min="0"
                    wire:model="form.reorder_level"
                />

                <x-ui.select
                    label="وضعیت"
                    name="form.status"
                    :options="$statusOptions"
                    wire:model="form.status"
                />

                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea
                        label="توضیحات"
                        name="form.description"
                        rows="3"
                        wire:model="form.description"
                    />
                </div>

            </div>
        </x-ui.card>

        <div class="flex justify-end gap-3">
            <x-ui.button
                variant="secondary"
                href="{{ route('sales.items.index') }}"
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
