<?php

namespace App\Livewire\Pages\Reports;

use App\Models\AttendanceSummary;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Guardian;
use App\Models\PayrollRecord;
use App\Models\Salary;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\StudentSale;
use App\Models\StudentTransport;
use Livewire\Component;

class ReportDashboard extends Component
{
    public function render()
    {
        return view('livewire.pages.reports.report-dashboard', $this->viewData())->layout('layouts.app', [
            'title' => $this->title(),
            'breadcrumbs' => [
                ['label' => 'داشبورد', 'url' => route('dashboard')],
                ['label' => 'گزارش ها', 'url' => route('reports.index')],
                ['label' => $this->title()],
            ],
        ]);
    }

    private function viewData(): array
    {
        return [
            'title' => $this->title(),
            'cards' => match (true) {
                request()->routeIs('reports.attendance') => $this->attendanceCards(),
                request()->routeIs('reports.finance') => $this->financeCards(),
                request()->routeIs('reports.exams') => $this->examCards(),
                default => $this->studentCards(),
            },
        ];
    }

    private function title(): string
    {
        return match (true) {
            request()->routeIs('reports.attendance') => 'گزارش حاضری',
            request()->routeIs('reports.finance') => 'گزارش مالی',
            request()->routeIs('reports.exams') => 'گزارش امتحانات',
            default => 'گزارش شاگردان',
        };
    }

    private function studentCards(): array
    {
        return [
            ['label' => 'مجموع شاگردان', 'value' => Student::query()->count()],
            ['label' => 'شاگردان فعال', 'value' => Student::query()->where('status', 'active')->count()],
            ['label' => 'سرپرستان', 'value' => Guardian::query()->count()],
        ];
    }

    private function attendanceCards(): array
    {
        return [
            ['label' => 'حاضری امروز', 'value' => AttendanceSummary::query()->whereDate('date', today())->count()],
            ['label' => 'حاضر', 'value' => AttendanceSummary::query()->whereDate('date', today())->where('status', 'present')->count()],
            ['label' => 'غیر حاضر', 'value' => AttendanceSummary::query()->whereDate('date', today())->where('status', 'absent')->count()],
        ];
    }

    private function financeCards(): array
    {
        $feeIncome = (float) FeePayment::query()->sum('amount_paid');
        $salesIncome = (float) StudentSale::query()->sum('paid_amount');
        $transportIncome = (float) StudentTransport::query()->sum('fee_amount');
        $expenses = (float) Expense::query()->sum('amount');
        $payroll = (float) PayrollRecord::query()->sum('net_salary') + (float) Salary::query()->sum('net_salary');
        $income = $feeIncome + $salesIncome + $transportIncome;
        $outgoing = $expenses + $payroll;

        return [
            ['label' => 'پرداخت فیس', 'value' => number_format($feeIncome, 2)],
            ['label' => 'عواید فروش', 'value' => number_format($salesIncome, 2)],
            ['label' => 'فیس ترانسپورت', 'value' => number_format($transportIncome, 2)],
            ['label' => 'مصارف', 'value' => number_format($expenses, 2)],
            ['label' => 'معاشات', 'value' => number_format($payroll, 2)],
            ['label' => 'بیلانس', 'value' => number_format($income - $outgoing, 2)],
        ];
    }

    private function examCards(): array
    {
        return [
            ['label' => 'نمرات ثبت شده', 'value' => StudentResult::query()->count()],
            ['label' => 'میانگین نمرات', 'value' => number_format((float) StudentResult::query()->avg('marks_obtained'), 2)],
            ['label' => 'امتحانات', 'value' => StudentResult::query()->distinct('exam_name')->count('exam_name')],
        ];
    }
}
