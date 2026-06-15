<div class="space-y-6">
    <section class="flex flex-col gap-4 rounded-2xl border border-white/70 bg-white/85 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">ترانسپورت</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-950 dark:text-white">{{ $title }}</h2>
        </div>
        <x-ui.button variant="secondary" href="{{ route('transport.index') }}" icon="chevron-right" wire:navigate>برگشت</x-ui.button>
    </section>

    @if ($errors->any()) <x-ui.alert variant="error" title="لطفا فورم را بررسی کنید.">بعضی فیلدها درست تکمیل نشده‌اند.</x-ui.alert> @endif

    <form wire:submit="save" class="space-y-6">
        <x-ui.card title="معلومات موتر" icon="truck">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نمبر پلیت" name="form.vehicle_plate_number" wire:model="form.vehicle_plate_number" />
                <x-ui.input type="number" label="ظرفیت" name="form.vehicle_capacity" min="1" wire:model="form.vehicle_capacity" />
                <x-ui.input label="نوع موتر" name="form.vehicle_type" placeholder="بس، وین، کاستر..." wire:model="form.vehicle_type" />
                <x-ui.select label="وضعیت" name="form.status" :options="$statusOptions" wire:model="form.status" />
            </div>
        </x-ui.card>

        <x-ui.card title="معلومات راننده" icon="identification">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نام راننده" name="form.driver_name" wire:model="form.driver_name" />
                <x-ui.input label="شماره تماس" name="form.driver_phone" wire:model="form.driver_phone" />
                <x-ui.input label="نمبر لایسنس" name="form.driver_license_number" wire:model="form.driver_license_number" />
                <x-ui.input type="number" label="معاش ماهانه راننده" name="form.driver_monthly_salary" min="0" step="0.01" wire:model="form.driver_monthly_salary" />
            </div>
        </x-ui.card>

        <x-ui.card title="معلومات مسیر" icon="link">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <x-ui.input label="نام مسیر" name="form.route_name" wire:model="form.route_name" />
                <x-ui.input label="از ساحه" name="form.pickup_area" wire:model="form.pickup_area" />
                <x-ui.input label="به ساحه" name="form.dropoff_area" wire:model="form.dropoff_area" />
                <x-ui.input type="number" label="فیس ماهانه" name="form.monthly_fee" min="0" step="0.01" wire:model="form.monthly_fee" />
                <div class="md:col-span-2 xl:col-span-4">
                    <x-ui.textarea label="یادداشت" name="form.note" rows="3" wire:model="form.note" />
                </div>
            </div>
        </x-ui.card>

        <div class="flex justify-end gap-3">
            <x-ui.button variant="secondary" href="{{ route('transport.index') }}" wire:navigate>لغو</x-ui.button>
            <x-ui.button type="submit" icon="check">ذخیره</x-ui.button>
        </div>
    </form>
</div>
