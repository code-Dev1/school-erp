@props([
    'roles' => [],
    'permissions' => [],
    'name' => 'permissions',
    'values' => [],
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950']) }}>
    <div class="overflow-x-auto ui-scrollbar">
        <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
            <thead class="bg-slate-50 dark:bg-slate-900">
                <tr>
                    <th scope="col" class="sticky start-0 z-10 bg-slate-50 px-4 py-3 text-start font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">Permission</th>
                    @foreach ($roles as $roleKey => $roleLabel)
                        <th scope="col" class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-200">{{ is_string($roleKey) ? $roleLabel : $roleLabel }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($permissions as $permissionKey => $permissionLabel)
                    @php $permissionValue = is_string($permissionKey) ? $permissionKey : $permissionLabel; @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/60">
                        <th scope="row" class="sticky start-0 bg-white px-4 py-3 text-start font-medium text-slate-800 dark:bg-slate-950 dark:text-slate-100">
                            {{ $permissionLabel }}
                        </th>
                        @foreach ($roles as $roleKey => $roleLabel)
                            @php
                                $roleValue = is_string($roleKey) ? $roleKey : $roleLabel;
                                $checked = (bool) data_get($values, $roleValue.'.'.$permissionValue, false);
                            @endphp
                            <td class="px-4 py-3 text-center">
                                <input
                                    type="checkbox"
                                    name="{{ $name }}[{{ $roleValue }}][{{ $permissionValue }}]"
                                    value="1"
                                    class="rounded border-slate-300 text-slate-950 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-sky-400"
                                    @checked($checked)
                                >
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
