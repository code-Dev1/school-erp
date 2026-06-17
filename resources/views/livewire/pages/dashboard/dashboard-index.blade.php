@php
    $cardsBySection = collect($dashboard['cards'])->groupBy('section');
    $chartsBySection = collect($dashboard['charts'])->groupBy('section');
@endphp

<div id="school-dashboard" class="space-y-6" dir="rtl">
    <section class="rounded-xl border border-white/70 bg-white/90 p-5 shadow-xl shadow-slate-950/[0.04] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/80">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">داشبورد مکتب</p>
                <h2 class="mt-1 text-2xl font-bold tracking-normal text-slate-950 dark:text-white">خلاصه مدیریتی و تحلیلی</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    از {{ $dashboard['range']['from_jalali'] }} تا {{ $dashboard['range']['to_jalali'] }}
                </p>
            </div>

            <form wire:submit.prevent="applyFilters" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
                <label class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">دوره</span>
                    <select wire:model.live="period" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-800 dark:bg-slate-900 dark:text-white">
                        @foreach ($periodOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">از تاریخ</span>
                    <input type="date" wire:model.defer="date_from" @disabled($period !== 'custom') class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-900/50">
                </label>

                <label class="space-y-1">
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">تا تاریخ</span>
                    <input type="date" wire:model.defer="date_to" @disabled($period !== 'custom') class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-900/50">
                </label>

                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/40 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                    <x-ui.icon name="funnel" class="h-4 w-4" />
                    اعمال فیلتر
                </button>

                <div wire:loading.flex class="items-center gap-2 rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 dark:border-indigo-400/20 dark:bg-indigo-400/10 dark:text-indigo-200">
                    <x-ui.icon name="arrow-path" class="h-4 w-4 animate-spin" />
                    در حال تازه‌سازی
                </div>
            </form>
        </div>
    </section>

    @foreach ($dashboard['sections'] as $section)
        @php
            $sectionCards = $cardsBySection->get($section, collect());
        @endphp
        @if ($sectionCards->isNotEmpty())
            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-950 dark:text-white">{{ $sectionLabels[$section] ?? $section }}</h3>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($sectionCards as $card)
                        <x-ui.stat-card
                            :label="$card['label']"
                            :value="$card['value']"
                            :icon="$card['icon']"
                            :accent="$card['accent']"
                            wire:key="dashboard-card-{{ $card['key'] }}"
                        />
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-950 dark:text-white">چارت‌ها</h3>
        </div>

        @forelse ($dashboard['sections'] as $section)
            @php
                $sectionCharts = $chartsBySection->get($section, collect());
            @endphp
            @if ($sectionCharts->isNotEmpty())
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $sectionLabels[$section] ?? $section }}</p>
                    <div class="grid gap-4 xl:grid-cols-2">
                        @foreach ($sectionCharts as $chart)
                            @php
                                $hasData = collect($chart['datasets'] ?? [])
                                    ->flatMap(fn ($dataset) => $dataset['data'] ?? [])
                                    ->filter(fn ($value) => (float) $value > 0)
                                    ->isNotEmpty();
                            @endphp
                            <x-ui.card class="relative overflow-hidden" wire:key="dashboard-chart-card-{{ $chart['key'] }}">
                                <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/70 text-sm font-semibold text-slate-600 backdrop-blur-sm dark:bg-slate-950/70 dark:text-slate-300">
                                    <x-ui.icon name="arrow-path" class="ml-2 h-4 w-4 animate-spin" />
                                    در حال بارگذاری چارت
                                </div>

                                <div class="mb-4 flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-bold text-slate-950 dark:text-white">{{ $chart['title'] }}</h4>
                                    <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-900 dark:text-slate-400">
                                        {{ $sectionLabels[$chart['section']] ?? $chart['section'] }}
                                    </span>
                                </div>

                                @if ($hasData)
                                    <div class="h-72">
                                        <canvas id="dashboard-chart-{{ $chart['key'] }}"></canvas>
                                    </div>
                                @else
                                    <div class="flex h-72 flex-col items-center justify-center rounded-lg border border-dashed border-slate-200 bg-slate-50 text-center dark:border-slate-800 dark:bg-slate-900/50">
                                        <x-ui.icon name="chart-bar" class="h-8 w-8 text-slate-300 dark:text-slate-600" />
                                        <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">برای این چارت فعلا دیتا موجود نیست</p>
                                    </div>
                                @endif
                            </x-ui.card>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <x-ui.card>
                <div class="py-10 text-center text-sm font-semibold text-slate-500 dark:text-slate-400">برای نقش فعلی چارت قابل نمایش موجود نیست.</div>
            </x-ui.card>
        @endforelse
    </section>

    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-950 dark:text-white">جدول‌ها و ویجت‌ها</h3>
        </div>

        <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
            @foreach ($dashboard['tables'] as $key => $table)
                <x-ui.card wire:key="dashboard-table-{{ $key }}">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h4 class="text-sm font-bold text-slate-950 dark:text-white">{{ $tableTitles[$key] ?? $key }}</h4>
                        <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 dark:bg-slate-900 dark:text-slate-400">
                            {{ $sectionLabels[$table['section']] ?? $table['section'] }}
                        </span>
                    </div>

                    @if (count($table['rows']) > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach ($table['rows'] as $row)
                                <div class="flex items-center justify-between gap-3 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $row['title'] ?: 'بدون عنوان' }}</p>
                                        <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ $row['meta'] ?: 'بدون جزئیات' }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                        {{ $row['value'] ?: '-' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex h-40 flex-col items-center justify-center rounded-lg border border-dashed border-slate-200 bg-slate-50 text-center dark:border-slate-800 dark:bg-slate-900/50">
                            <x-ui.icon name="document-chart-bar" class="h-7 w-7 text-slate-300 dark:text-slate-600" />
                            <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">فعلا دیتایی برای نمایش نیست</p>
                        </div>
                    @endif
                </x-ui.card>
            @endforeach
        </div>
    </section>

    <script type="application/json" id="dashboard-chart-data">@json($dashboard['charts'])</script>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
        <script>
            window.schoolDashboardCharts = window.schoolDashboardCharts || {};

            window.renderSchoolDashboardCharts = function () {
                const dataNode = document.getElementById('dashboard-chart-data');
                if (!dataNode) {
                    return;
                }

                if (!window.Chart) {
                    window.setTimeout(window.renderSchoolDashboardCharts, 150);
                    return;
                }

                let charts = [];
                try {
                    charts = JSON.parse(dataNode.textContent || '[]');
                } catch (error) {
                    charts = [];
                }

                Object.values(window.schoolDashboardCharts).forEach((chart) => chart.destroy());
                window.schoolDashboardCharts = {};

                const isDark = document.documentElement.classList.contains('dark');
                const tickColor = isDark ? '#cbd5e1' : '#475569';
                const gridColor = isDark ? 'rgba(148, 163, 184, 0.14)' : 'rgba(100, 116, 139, 0.16)';

                charts.forEach((chart) => {
                    const canvas = document.getElementById(`dashboard-chart-${chart.key}`);
                    if (!canvas) {
                        return;
                    }

                    const hasData = (chart.datasets || []).flatMap((dataset) => dataset.data || []).some((value) => Number(value) > 0);
                    if (!hasData) {
                        return;
                    }

                    const datasets = (chart.datasets || []).map((dataset) => ({
                        ...dataset,
                        borderWidth: chart.type === 'doughnut' ? 0 : 2,
                        pointRadius: chart.type === 'line' ? 2 : 0,
                        maxBarThickness: 34,
                    }));

                    window.schoolDashboardCharts[chart.key] = new Chart(canvas, {
                        type: chart.type,
                        data: {
                            labels: chart.labels || [],
                            datasets,
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: tickColor,
                                        boxWidth: 10,
                                        usePointStyle: true,
                                        font: {
                                            family: 'Vazirmatn, Tahoma, sans-serif',
                                            size: 11,
                                        },
                                    },
                                },
                                tooltip: {
                                    rtl: true,
                                    textDirection: 'rtl',
                                    backgroundColor: isDark ? '#020617' : '#ffffff',
                                    titleColor: isDark ? '#ffffff' : '#0f172a',
                                    bodyColor: isDark ? '#cbd5e1' : '#334155',
                                    borderColor: isDark ? '#1e293b' : '#e2e8f0',
                                    borderWidth: 1,
                                },
                            },
                            scales: chart.type === 'doughnut' ? {} : {
                                x: {
                                    grid: {
                                        color: gridColor,
                                    },
                                    ticks: {
                                        color: tickColor,
                                        maxRotation: 0,
                                        autoSkip: true,
                                        font: {
                                            family: 'Vazirmatn, Tahoma, sans-serif',
                                            size: 10,
                                        },
                                    },
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor,
                                    },
                                    ticks: {
                                        color: tickColor,
                                        font: {
                                            family: 'Vazirmatn, Tahoma, sans-serif',
                                            size: 10,
                                        },
                                    },
                                },
                            },
                        },
                    });
                });
            };

            document.addEventListener('DOMContentLoaded', () => window.renderSchoolDashboardCharts());
            document.addEventListener('livewire:navigated', () => window.renderSchoolDashboardCharts());
            document.addEventListener('livewire:init', () => {
                Livewire.on('dashboard-updated', () => window.setTimeout(window.renderSchoolDashboardCharts, 80));
            });
        </script>
    @endpush
@endonce
