<?php

namespace App\Livewire\Pages\Dashboard;

use App\Services\Dashboard\SchoolDashboardData;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class DashboardIndex extends Component
{
    #[Url]
    public string $period = 'this_month';

    #[Url]
    public ?string $date_from = null;

    #[Url]
    public ?string $date_to = null;

    public function mount(): void
    {
        $this->syncDatesWithPeriod();
    }

    public function updatedPeriod(): void
    {
        $this->syncDatesWithPeriod();
        $this->dispatch('dashboard-updated');
    }

    public function applyFilters(): void
    {
        if ($this->period !== 'custom') {
            $this->syncDatesWithPeriod();
        }

        $this->dispatch('dashboard-updated');
    }

    public function render(SchoolDashboardData $dashboardData)
    {
        $payload = $dashboardData->forUser(auth()->user(), [
            'period' => $this->period,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ]);

        return view('livewire.pages.dashboard.dashboard-index', [
            'dashboard' => $payload,
            'periodOptions' => $this->periodOptions(),
            'sectionLabels' => $this->sectionLabels(),
            'tableTitles' => $this->tableTitles(),
        ])->layout('layouts.app', [
            'title' => 'داشبورد',
            'breadcrumbs' => [
                ['label' => 'داشبورد'],
            ],
        ]);
    }

    private function syncDatesWithPeriod(): void
    {
        $today = today();

        [$from, $to] = match ($this->period) {
            'today' => [$today->copy(), $today->copy()],
            'this_week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'this_year' => [$today->copy()->startOfYear(), $today->copy()->endOfYear()],
            'custom' => [
                Carbon::parse($this->date_from ?: $today->copy()->startOfMonth()),
                Carbon::parse($this->date_to ?: $today),
            ],
            default => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
        };

        $this->date_from = $from->toDateString();
        $this->date_to = $to->toDateString();
    }

    private function periodOptions(): array
    {
        return [
            'today' => 'امروز',
            'this_week' => 'این هفته',
            'this_month' => 'این ماه',
            'this_year' => 'امسال',
            'custom' => 'تاریخ دلخواه',
        ];
    }

    private function sectionLabels(): array
    {
        return [
            'overview' => 'خلاصه عمومی',
            'finance' => 'مالی',
            'academic' => 'آموزشی',
            'attendance' => 'حاضری و بیومتریک',
            'transport' => 'ترانسپورت',
            'library' => 'کتابخانه و فروش',
        ];
    }

    private function tableTitles(): array
    {
        return [
            'recentFeePayments' => 'پرداخت‌های اخیر فیس',
            'recentExpenses' => 'مصارف اخیر',
            'recentSalaries' => 'معاشات اخیر',
            'recentSales' => 'فروشات اخیر کتاب و یونیفورم',
            'recentBiometricLogs' => 'لاگ‌های اخیر بیومتریک',
            'overdueFeeStudents' => 'شاگردان دارای فیس معوقه',
            'absentStudentsToday' => 'شاگردان غیر حاضر امروز',
            'upcomingExams' => 'امتحانات پیش‌رو',
            'todayTimetable' => 'تقسیم اوقات امروز',
            'lowStockItems' => 'آیتم‌های کم موجودی',
            'overdueLoans' => 'امانت‌های معوقه کتابخانه',
            'recentActivities' => 'فعالیت‌های اخیر سیستم',
        ];
    }
}
