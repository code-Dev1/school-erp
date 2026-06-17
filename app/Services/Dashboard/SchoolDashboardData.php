<?php

namespace App\Services\Dashboard;

use App\Enums\Biometric\AttendanceStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use App\Models\AcademicClass;
use App\Models\AttendanceSummary;
use App\Models\BiometricDevice;
use App\Models\BiometricLog;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\FeeAlert;
use App\Models\FeePayment;
use App\Models\Guardian;
use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\PayrollRecord;
use App\Models\ReportExport;
use App\Models\Salary;
use App\Models\Student;
use App\Models\StudentResult;
use App\Models\StudentSale;
use App\Models\StudentSaleItem;
use App\Models\StudentTransport;
use App\Models\Subject;
use App\Models\Timetable;
use App\Models\TransportService;
use App\Models\User;
use App\Support\School\JalaliDate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SchoolDashboardData
{
    public function forUser(User $user, array $filters): array
    {
        $range = $this->range($filters);
        $sections = $this->sectionsFor($user);
        $cacheKey = 'school_dashboard.'.md5(json_encode([
            'user' => $user->id,
            'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames()->all() : [],
            'sections' => $sections,
            'from' => $range['from']->toDateString(),
            'to' => $range['to']->toDateString(),
        ]));

        return Cache::remember($cacheKey, now()->addSeconds(60), function () use ($range, $sections): array {
            $cards = $this->cards($range);
            $charts = $this->charts($range);
            $tables = $this->tables($range);

            return [
                'range' => [
                    'from' => $range['from']->toDateString(),
                    'to' => $range['to']->toDateString(),
                    'from_jalali' => JalaliDate::format($range['from']),
                    'to_jalali' => JalaliDate::format($range['to']),
                ],
                'sections' => $sections,
                'cards' => array_values(array_filter($cards, fn (array $card) => in_array($card['section'], $sections, true))),
                'charts' => array_values(array_filter($charts, fn (array $chart) => in_array($chart['section'], $sections, true))),
                'tables' => array_filter($tables, fn (array $table) => in_array($table['section'], $sections, true)),
            ];
        });
    }

    public function range(array $filters): array
    {
        $today = today();
        $period = $filters['period'] ?? 'this_month';

        [$from, $to] = match ($period) {
            'today' => [$today->copy(), $today->copy()],
            'this_week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'this_year' => [$today->copy()->startOfYear(), $today->copy()->endOfYear()],
            'custom' => [
                Carbon::parse($filters['date_from'] ?? $today->copy()->startOfMonth()),
                Carbon::parse($filters['date_to'] ?? $today),
            ],
            default => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
        };

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to, $from];
        }

        return ['from' => $from->startOfDay(), 'to' => $to->endOfDay()];
    }

    private function sectionsFor(User $user): array
    {
        $all = ['overview', 'finance', 'academic', 'attendance', 'transport', 'library'];

        if (! method_exists($user, 'hasAnyRole')) {
            return $all;
        }

        if ($user->hasAnyRole(['Super Admin', 'super-admin', 'Admin', 'admin'])) {
            return $all;
        }

        if (! $user->roles()->exists() && ! $user->permissions()->exists()) {
            return $all;
        }

        $sections = ['overview'];

        if ($user->hasAnyRole(['Principal', 'Manager', 'deputy', 'registrar', 'Viewer']) || $user->can('read_academic_dashboard')) {
            array_push($sections, 'academic', 'attendance', 'transport', 'library');
        }

        if ($user->hasAnyRole(['Accountant', 'accountant']) || $user->can('read_finance_dashboard')) {
            array_push($sections, 'finance', 'transport', 'library');
        }

        if ($user->hasAnyRole(['Teacher', 'teacher']) || $user->can('read_academic_dashboard')) {
            array_push($sections, 'academic', 'attendance');
        }

        if ($user->can('read_attendance_dashboard')) {
            $sections[] = 'attendance';
        }

        if ($user->can('read_transport_dashboard')) {
            $sections[] = 'transport';
        }

        if ($user->can('read_library_dashboard')) {
            $sections[] = 'library';
        }

        return array_values(array_unique($sections));
    }

    private function cards(array $range): array
    {
        $today = today();
        $studentAttendance = AttendanceSummary::query()
            ->where('person_type', Student::class)
            ->whereDate('date', $today);
        $employeeAttendance = AttendanceSummary::query()
            ->where('person_type', Employee::class)
            ->whereDate('date', $today);

        $todayIncome = $this->feeIncome($today, $today) + $this->salesIncome($today, $today) + $this->transportIncome($today, $today);
        $monthlyIncome = $this->feeIncome($today->copy()->startOfMonth(), $today->copy()->endOfMonth())
            + $this->salesIncome($today->copy()->startOfMonth(), $today->copy()->endOfMonth())
            + $this->transportIncome($today->copy()->startOfMonth(), $today->copy()->endOfMonth());
        $todayExpenses = $this->expenseTotal($today, $today);
        $monthlyExpenses = $this->expenseTotal($today->copy()->startOfMonth(), $today->copy()->endOfMonth());
        $salaryPaid = (float) PayrollRecord::query()->whereNotNull('paid_at')->sum('net_salary') + (float) Salary::query()->whereNotNull('paid_at')->sum('net_salary');
        $totalIncome = $this->feeIncome() + $this->salesIncome() + $this->transportIncome();
        $totalOutgoing = (float) Expense::query()->sum('amount') + $salaryPaid;

        return [
            $this->card('total_students', 'مجموع شاگردان', Student::query()->count(), 'academic-cap', 'indigo', 'overview'),
            $this->card('active_students', 'شاگردان فعال', Student::query()->where('status', StudentStatus::Active->value)->count(), 'check', 'emerald', 'overview'),
            $this->card('new_students', 'شاگردان جدید', Student::query()->where('student_type', 'new')->count(), 'sparkles', 'sky', 'overview'),
            $this->card('transferred_students', 'شاگردان تبدیلی', Student::query()->where('status', StudentStatus::Transferred->value)->orWhere('student_type', 'transferred')->count(), 'arrow-path', 'amber', 'overview'),
            $this->card('total_guardians', 'مجموع سرپرستان', Guardian::query()->count(), 'users', 'sky', 'overview'),
            $this->card('total_teachers', 'مجموع استادان', Employee::query()->where('type', EmployeeType::Teacher->value)->count(), 'users', 'indigo', 'academic'),
            $this->card('total_employees', 'مجموع کارمندان', Employee::query()->count(), 'identification', 'sky', 'overview'),
            $this->card('total_drivers', 'مجموع رانندگان', TransportService::query()->whereNotNull('driver_name')->distinct()->count('driver_name'), 'truck', 'amber', 'transport'),
            $this->card('total_classes', 'مجموع صنف‌ها', AcademicClass::query()->count(), 'building-office', 'indigo', 'academic'),
            $this->card('total_subjects', 'مجموع مضامین', Subject::query()->count(), 'book-open', 'sky', 'academic'),
            $this->card('total_vehicles', 'مجموع موترها', TransportService::query()->count(), 'truck', 'amber', 'transport'),
            $this->card('total_books', 'مجموع کتاب‌ها', LibraryBook::query()->sum('total_copies'), 'book-open', 'emerald', 'library'),
            $this->card('low_stock_items', 'آیتم‌های کم موجودی', $this->lowStockItemsCount(), 'rectangle-stack', 'rose', 'library'),
            $this->card('today_present_students', 'شاگردان حاضر امروز', (clone $studentAttendance)->where('status', AttendanceStatus::Present->value)->count(), 'check', 'emerald', 'attendance'),
            $this->card('today_absent_students', 'شاگردان غیر حاضر امروز', (clone $studentAttendance)->where('status', AttendanceStatus::Absent->value)->count(), 'x-mark', 'rose', 'attendance'),
            $this->card('today_late_students', 'شاگردان ناوقت امروز', (clone $studentAttendance)->where('status', AttendanceStatus::Late->value)->count(), 'clock', 'amber', 'attendance'),
            $this->card('today_present_employees', 'کارمندان حاضر امروز', (clone $employeeAttendance)->where('status', AttendanceStatus::Present->value)->count(), 'check', 'emerald', 'attendance'),
            $this->card('today_absent_employees', 'کارمندان غیر حاضر امروز', (clone $employeeAttendance)->where('status', AttendanceStatus::Absent->value)->count(), 'x-mark', 'rose', 'attendance'),
            $this->card('unmatched_biometric_logs', 'لاگ‌های بیومتریک نامطابق', BiometricLog::query()->whereNull('person_id')->count(), 'finger-print', 'rose', 'attendance'),
            $this->card('fee_due_students', 'شاگردان دارای فیس باقی', FeeAlert::query()->whereIn('status', ['open', 'pending'])->distinct()->count('student_id'), 'currency-dollar', 'amber', 'finance'),
            $this->card('overdue_fee_students', 'شاگردان فیس معوقه', FeeAlert::query()->whereDate('due_date', '<', $today)->whereIn('status', ['open', 'pending'])->distinct()->count('student_id'), 'exclamation-triangle', 'rose', 'finance'),
            $this->card('today_income', 'عواید امروز', $this->money($todayIncome), 'banknotes', 'emerald', 'finance'),
            $this->card('today_expenses', 'مصارف امروز', $this->money($todayExpenses), 'banknotes', 'rose', 'finance'),
            $this->card('monthly_income', 'عواید ماه جاری', $this->money($monthlyIncome), 'chart-bar', 'emerald', 'finance'),
            $this->card('monthly_expenses', 'مصارف ماه جاری', $this->money($monthlyExpenses), 'chart-bar', 'rose', 'finance'),
            $this->card('total_unpaid_fees', 'مجموع فیس پرداخت‌نشده', $this->money((float) FeePayment::query()->sum('remaining_amount')), 'currency-dollar', 'amber', 'finance'),
            $this->card('total_salaries_paid', 'مجموع معاشات پرداخت‌شده', $this->money($salaryPaid), 'currency-dollar', 'sky', 'finance'),
            $this->card('net_balance', 'بیلانس خالص', $this->money($totalIncome - $totalOutgoing), 'chart-bar', ($totalIncome - $totalOutgoing) >= 0 ? 'emerald' : 'rose', 'finance'),
        ];
    }

    private function charts(array $range): array
    {
        return array_merge(
            $this->financeCharts($range),
            $this->academicCharts($range),
            $this->attendanceCharts($range),
            $this->transportCharts($range),
            $this->libraryCharts($range),
        );
    }

    private function financeCharts(array $range): array
    {
        $months = $this->lastMonths(12);
        $days = $this->lastDays(14);

        return [
            $this->lineChart('monthly_income_expense', 'عواید در برابر مصارف ماهانه', 'finance', $months['labels'], [
                $this->dataset('عواید', array_map(fn (array $month) => $this->feeIncome($month['from'], $month['to']) + $this->salesIncome($month['from'], $month['to']) + $this->transportIncome($month['from'], $month['to']), $months['ranges']), '#10b981'),
                $this->dataset('مصارف', array_map(fn (array $month) => $this->expenseTotal($month['from'], $month['to']), $months['ranges']), '#ef4444'),
            ]),
            $this->barChart('fee_collection_by_month', 'جمع‌آوری فیس به تفکیک ماه', 'finance', $months['labels'], [
                $this->dataset('فیس', array_map(fn (array $month) => $this->feeIncome($month['from'], $month['to']), $months['ranges']), '#6366f1'),
            ]),
            $this->doughnutChart('expense_by_category', 'مصارف به تفکیک کتگوری', 'finance', $this->expenseByCategory($range)),
            $this->doughnutChart('salary_cost_person_type', 'مصرف معاشات بر اساس نوع شخص', 'finance', $this->salaryCostByType()),
            $this->doughnutChart('income_breakdown', 'تفکیک عواید', 'finance', $this->incomeBreakdown($range)),
            $this->lineChart('daily_income_expense', 'عواید و مصارف روزانه', 'finance', $days['labels'], [
                $this->dataset('عواید', array_map(fn (Carbon $day) => $this->feeIncome($day, $day) + $this->salesIncome($day, $day) + $this->transportIncome($day, $day), $days['ranges']), '#14b8a6'),
                $this->dataset('مصارف', array_map(fn (Carbon $day) => $this->expenseTotal($day, $day), $days['ranges']), '#f97316'),
            ]),
            $this->barChart('unpaid_fees_by_class', 'فیس پرداخت‌نشده به تفکیک صنف', 'finance', ...$this->unpaidFeesByClass()),
            $this->barChart('transport_income_cost', 'عواید و مصارف ترانسپورت', 'finance', ['عواید', 'مصارف رانندگان'], [
                $this->dataset('ترانسپورت', [
                    $this->transportIncome($range['from'], $range['to']),
                    (float) TransportService::query()->sum('driver_monthly_salary'),
                ], '#f59e0b'),
            ]),
        ];
    }

    private function academicCharts(array $range): array
    {
        $months = $this->lastMonths(12);

        return [
            $this->barChart('students_by_class', 'شاگردان به تفکیک صنف', 'academic', ...$this->studentsByClass()),
            $this->doughnutChart('students_by_gender', 'شاگردان به تفکیک جنسیت', 'academic', [
                'labels' => ['ذکور', 'اناث'],
                'values' => [
                    Student::query()->where('gender', StudentGender::Male->value)->count(),
                    Student::query()->where('gender', StudentGender::Female->value)->count(),
                ],
            ]),
            $this->doughnutChart('students_by_status', 'شاگردان به تفکیک حالت', 'academic', [
                'labels' => ['فعال', 'تبدیلی', 'فارغ', 'اخراج'],
                'values' => [
                    Student::query()->where('status', StudentStatus::Active->value)->count(),
                    Student::query()->where('status', StudentStatus::Transferred->value)->count(),
                    Student::query()->where('status', StudentStatus::Graduated->value)->count(),
                    Student::query()->where('status', StudentStatus::Expelled->value)->count(),
                ],
            ]),
            $this->barChart('admissions_by_month', 'ثبت نام جدید به تفکیک ماه', 'academic', $months['labels'], [
                $this->dataset('ثبت نام', array_map(fn (array $month) => Student::query()->whereBetween('admission_date', [$month['from'], $month['to']])->count(), $months['ranges']), '#0ea5e9'),
            ]),
            $this->barChart('marks_average_by_class', 'میانگین نمرات به تفکیک صنف', 'academic', ...$this->marksAverageByClass()),
            $this->doughnutChart('pass_fail_summary', 'خلاصه کامیاب و ناکام', 'academic', $this->passFailSummary()),
            $this->barChart('top_students_average', '۱۰ شاگرد برتر بر اساس میانگین', 'academic', ...$this->studentAverageMarks('desc')),
            $this->barChart('weak_students_low_marks', 'شاگردان ضعیف بر اساس نمرات پایین', 'academic', ...$this->studentAverageMarks('asc')),
        ];
    }

    private function attendanceCharts(array $range): array
    {
        $days = $this->lastDays(30);

        return [
            $this->doughnutChart('today_attendance_summary', 'خلاصه حاضری امروز', 'attendance', $this->attendanceStatusCounts(today(), today(), Student::class)),
            $this->lineChart('attendance_trend_30_days', 'روند حاضری ۳۰ روز اخیر', 'attendance', $days['labels'], [
                $this->dataset('حاضر', array_map(fn (Carbon $day) => AttendanceSummary::query()->whereDate('date', $day)->where('status', AttendanceStatus::Present->value)->count(), $days['ranges']), '#10b981'),
                $this->dataset('غیر حاضر', array_map(fn (Carbon $day) => AttendanceSummary::query()->whereDate('date', $day)->where('status', AttendanceStatus::Absent->value)->count(), $days['ranges']), '#ef4444'),
                $this->dataset('ناوقت', array_map(fn (Carbon $day) => AttendanceSummary::query()->whereDate('date', $day)->where('status', AttendanceStatus::Late->value)->count(), $days['ranges']), '#f59e0b'),
            ]),
            $this->barChart('class_attendance_percentage', 'فیصدی حاضری صنف‌ها', 'attendance', ...$this->classAttendancePercentage($range)),
            $this->doughnutChart('employee_attendance_summary', 'خلاصه حاضری کارمندان', 'attendance', $this->attendanceStatusCounts($range['from'], $range['to'], Employee::class)),
            $this->doughnutChart('teacher_attendance_summary', 'خلاصه حاضری استادان', 'attendance', $this->teacherAttendanceSummary($range)),
            $this->barChart('late_arrivals_chart', 'آمدن‌های ناوقت', 'attendance', $days['labels'], [
                $this->dataset('ناوقت', array_map(fn (Carbon $day) => AttendanceSummary::query()->whereDate('date', $day)->where('status', AttendanceStatus::Late->value)->count(), $days['ranges']), '#f59e0b'),
            ]),
            $this->barChart('biometric_logs_by_device', 'لاگ‌های بیومتریک به تفکیک دستگاه', 'attendance', ...$this->biometricLogsByDevice()),
            $this->barChart('unmatched_biometric_logs_count', 'لاگ‌های بیومتریک نامطابق', 'attendance', ['نامطابق', 'مطابق'], [
                $this->dataset('لاگ‌ها', [
                    BiometricLog::query()->whereNull('person_id')->count(),
                    BiometricLog::query()->whereNotNull('person_id')->count(),
                ], '#e11d48'),
            ]),
        ];
    }

    private function transportCharts(array $range): array
    {
        $months = $this->lastMonths(12);

        return [
            $this->barChart('students_by_route', 'شاگردان به تفکیک مسیر', 'transport', ...$this->studentsByRoute()),
            $this->barChart('vehicle_usage_summary', 'استفاده از موترها', 'transport', ...$this->vehicleUsage()),
            $this->barChart('driver_salary_cost', 'مصرف معاش رانندگان', 'transport', ...$this->driverSalaryCost()),
            $this->barChart('transport_fee_collection', 'جمع‌آوری فیس ترانسپورت', 'transport', $months['labels'], [
                $this->dataset('فیس ترانسپورت', array_map(fn (array $month) => $this->transportIncome($month['from'], $month['to']), $months['ranges']), '#f59e0b'),
            ]),
            $this->barChart('routes_active_students', 'مسیرهای دارای شاگرد فعال', 'transport', ...$this->studentsByRoute(activeOnly: true)),
            $this->doughnutChart('vehicles_status', 'موترهای فعال و غیرفعال', 'transport', [
                'labels' => ['فعال', 'غیرفعال'],
                'values' => [
                    TransportService::query()->where('status', 'active')->count(),
                    TransportService::query()->where('status', '!=', 'active')->count(),
                ],
            ]),
        ];
    }

    private function libraryCharts(array $range): array
    {
        $months = $this->lastMonths(12);

        return [
            $this->doughnutChart('book_stock_summary', 'خلاصه موجودی کتاب‌ها', 'library', [
                'labels' => ['نسخه‌های موجود', 'نسخه‌های امانت‌شده'],
                'values' => [
                    (float) LibraryBook::query()->sum('available_copies'),
                    max(0, (float) LibraryBook::query()->sum('total_copies') - (float) LibraryBook::query()->sum('available_copies')),
                ],
            ]),
            $this->barChart('borrowed_books', 'کتاب‌های امانت‌شده', 'library', ['امانت فعال'], [
                $this->dataset('کتاب', [LibraryLoan::query()->whereNull('returned_at')->count()], '#0ea5e9'),
            ]),
            $this->barChart('overdue_borrowed_books', 'امانت‌های معوقه', 'library', ['معوقه'], [
                $this->dataset('کتاب', [LibraryLoan::query()->whereNull('returned_at')->whereDate('due_at', '<', today())->count()], '#ef4444'),
            ]),
            $this->barChart('sales_by_month', 'فروش کتاب و یونیفورم به تفکیک ماه', 'library', $months['labels'], [
                $this->dataset('کتاب', array_map(fn (array $month) => $this->salesByCategory('book', $month['from'], $month['to']), $months['ranges']), '#6366f1'),
                $this->dataset('یونیفورم', array_map(fn (array $month) => $this->salesByCategory('uniform', $month['from'], $month['to']), $months['ranges']), '#14b8a6'),
            ]),
            $this->barChart('low_stock_books_uniforms', 'کتاب‌ها و یونیفورم‌های کم موجودی', 'library', ['کتاب', 'یونیفورم', 'دیگر'], [
                $this->dataset('کم موجودی', [
                    $this->lowStockItemsCount('book'),
                    $this->lowStockItemsCount('uniform'),
                    $this->lowStockItemsCount(),
                ], '#f59e0b'),
            ]),
            $this->barChart('top_selling_items', 'آیتم‌های پرفروش', 'library', ...$this->topSellingItems()),
        ];
    }

    private function tables(array $range): array
    {
        return [
            'recentFeePayments' => $this->table('finance', FeePayment::query()
                ->with('student:id,first_name,last_name,student_id')
                ->latest('payment_date')
                ->limit(6)
                ->get()
                ->map(fn (FeePayment $payment) => [
                    'title' => $this->studentName($payment->student),
                    'meta' => JalaliDate::format($payment->payment_date),
                    'value' => $this->money((float) ($payment->amount_paid ?: $payment->paid_amount)),
                ])->all()),
            'recentExpenses' => $this->table('finance', Expense::query()
                ->latest('expense_date')
                ->limit(6)
                ->get()
                ->map(fn (Expense $expense) => [
                    'title' => $expense->title,
                    'meta' => $expense->category ?: JalaliDate::format($expense->expense_date ?: $expense->date),
                    'value' => $this->money((float) $expense->amount),
                ])->all()),
            'recentSalaries' => $this->table('finance', PayrollRecord::query()
                ->with('employee:id,name,first_name,last_name')
                ->latest('paid_at')
                ->limit(6)
                ->get()
                ->map(fn (PayrollRecord $record) => [
                    'title' => $record->employee?->name ?: trim(($record->employee?->first_name ?? '').' '.($record->employee?->last_name ?? '')),
                    'meta' => $record->month.'/'.$record->year,
                    'value' => $this->money((float) $record->net_salary),
                ])->all()),
            'recentSales' => $this->table('library', StudentSale::query()
                ->with('student:id,first_name,last_name,student_id')
                ->latest('sold_at')
                ->limit(6)
                ->get()
                ->map(fn (StudentSale $sale) => [
                    'title' => $sale->invoice_number ?: $this->studentName($sale->student),
                    'meta' => JalaliDate::format($sale->sold_at),
                    'value' => $this->money((float) $sale->paid_amount),
                ])->all()),
            'recentBiometricLogs' => $this->table('attendance', BiometricLog::query()
                ->with('device:id,name')
                ->latest('timestamp')
                ->limit(6)
                ->get()
                ->map(fn (BiometricLog $log) => [
                    'title' => $log->device?->name ?: 'دستگاه نامشخص',
                    'meta' => $log->person_id ? 'مطابق' : 'نامطابق',
                    'value' => JalaliDate::format($log->timestamp),
                ])->all()),
            'overdueFeeStudents' => $this->table('finance', FeeAlert::query()
                ->with('student:id,first_name,last_name,student_id')
                ->whereDate('due_date', '<', today())
                ->whereIn('status', ['open', 'pending'])
                ->latest('due_date')
                ->limit(6)
                ->get()
                ->map(fn (FeeAlert $alert) => [
                    'title' => $this->studentName($alert->student),
                    'meta' => JalaliDate::format($alert->due_date),
                    'value' => $this->money((float) $alert->due_amount),
                ])->all()),
            'absentStudentsToday' => $this->table('attendance', AttendanceSummary::query()
                ->with('person')
                ->where('person_type', Student::class)
                ->whereDate('date', today())
                ->where('status', AttendanceStatus::Absent->value)
                ->limit(6)
                ->get()
                ->map(fn (AttendanceSummary $summary) => [
                    'title' => $this->studentName($summary->person),
                    'meta' => JalaliDate::format($summary->date),
                    'value' => 'غیر حاضر',
                ])->all()),
            'upcomingExams' => $this->table('academic', StudentResult::query()
                ->with(['academicClass:id,name', 'subject:id,name'])
                ->whereDate('exam_date', '>=', today())
                ->orderBy('exam_date')
                ->limit(6)
                ->get()
                ->map(fn (StudentResult $result) => [
                    'title' => $result->exam_name ?: 'امتحان',
                    'meta' => ($result->academicClass?->name ?: '').' - '.($result->subject?->name ?: ''),
                    'value' => JalaliDate::format($result->exam_date),
                ])->all()),
            'todayTimetable' => $this->table('academic', Timetable::query()
                ->with(['academicClass:id,name', 'section:id,name', 'subject:id,name', 'teacher:id,name,first_name,last_name'])
                ->where('day_of_week', strtolower(today()->englishDayOfWeek))
                ->orderBy('start_time')
                ->limit(8)
                ->get()
                ->map(fn (Timetable $item) => [
                    'title' => ($item->subject?->name ?: 'مضمون').' - '.($item->academicClass?->name ?: 'صنف'),
                    'meta' => $item->teacher?->name ?: trim(($item->teacher?->first_name ?? '').' '.($item->teacher?->last_name ?? '')),
                    'value' => substr((string) $item->start_time, 0, 5),
                ])->all()),
            'lowStockItems' => $this->table('library', DB::table('sale_items')
                ->whereColumn('stock_quantity', '<=', 'reorder_level')
                ->select('name', 'category', 'stock_quantity')
                ->orderBy('stock_quantity')
                ->limit(6)
                ->get()
                ->map(fn ($item) => [
                    'title' => $item->name,
                    'meta' => $item->category,
                    'value' => (string) $item->stock_quantity,
                ])->all()),
            'overdueLoans' => $this->table('library', LibraryLoan::query()
                ->with(['book:id,title', 'student:id,first_name,last_name,student_id'])
                ->whereNull('returned_at')
                ->whereDate('due_at', '<', today())
                ->orderBy('due_at')
                ->limit(6)
                ->get()
                ->map(fn (LibraryLoan $loan) => [
                    'title' => $loan->book?->title ?: 'کتاب',
                    'meta' => $this->studentName($loan->student),
                    'value' => JalaliDate::format($loan->due_at),
                ])->all()),
            'recentActivities' => $this->table('overview', ReportExport::query()
                ->with('generatedBy:id,name')
                ->latest('generated_at')
                ->limit(6)
                ->get()
                ->map(fn (ReportExport $export) => [
                    'title' => $export->title,
                    'meta' => $export->generatedBy?->name ?: 'سیستم',
                    'value' => JalaliDate::format($export->generated_at),
                ])->all()),
        ];
    }

    private function card(string $key, string $label, int|float|string $value, string $icon, string $accent, string $section): array
    {
        return compact('key', 'label', 'value', 'icon', 'accent', 'section');
    }

    private function table(string $section, array $rows): array
    {
        return compact('section', 'rows');
    }

    private function dataset(string $label, array $data, string $color): array
    {
        return [
            'label' => $label,
            'data' => array_map(fn ($value) => round((float) $value, 2), $data),
            'borderColor' => $color,
            'backgroundColor' => $color,
            'tension' => 0.35,
            'fill' => false,
        ];
    }

    private function lineChart(string $key, string $title, string $section, array $labels, array $datasets): array
    {
        return compact('key', 'title', 'section', 'labels', 'datasets') + ['type' => 'line'];
    }

    private function barChart(string $key, string $title, string $section, array $labels, array $datasets): array
    {
        return compact('key', 'title', 'section', 'labels', 'datasets') + ['type' => 'bar'];
    }

    private function doughnutChart(string $key, string $title, string $section, array $data): array
    {
        return [
            'key' => $key,
            'title' => $title,
            'section' => $section,
            'type' => 'doughnut',
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => $title,
                    'data' => array_map(fn ($value) => round((float) $value, 2), $data['values']),
                    'backgroundColor' => ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#0ea5e9', '#14b8a6', '#a855f7', '#f97316'],
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    private function feeIncome(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) $this->between(FeePayment::query(), 'payment_date', $from, $to)->sum(DB::raw('COALESCE(amount_paid, paid_amount, 0)'));
    }

    private function salesIncome(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) $this->between(StudentSale::query(), 'sold_at', $from, $to)->sum('paid_amount');
    }

    private function transportIncome(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) $this->between(StudentTransport::query(), 'starts_at', $from, $to)->sum('fee_amount');
    }

    private function expenseTotal(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) $this->between(Expense::query(), 'expense_date', $from, $to)->sum('amount');
    }

    private function between(Builder $query, string $column, ?Carbon $from, ?Carbon $to): Builder
    {
        if ($from && $to) {
            $query->whereBetween($column, [$from->toDateString(), $to->toDateString()]);
        }

        return $query;
    }

    private function lastMonths(int $count): array
    {
        $ranges = [];
        $labels = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $date = today()->subMonths($i);
            $ranges[] = ['from' => $date->copy()->startOfMonth(), 'to' => $date->copy()->endOfMonth()];
            $labels[] = JalaliDate::format($date->copy()->startOfMonth());
        }

        return compact('ranges', 'labels');
    }

    private function lastDays(int $count): array
    {
        $ranges = [];
        $labels = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $ranges[] = $date;
            $labels[] = JalaliDate::format($date);
        }

        return compact('ranges', 'labels');
    }

    private function expenseByCategory(array $range): array
    {
        $rows = Expense::query()
            ->whereBetween('expense_date', [$range['from']->toDateString(), $range['to']->toDateString()])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('category')->map(fn ($value) => $value ?: 'بدون کتگوری')->all(),
            'values' => $rows->pluck('total')->all(),
        ];
    }

    private function salaryCostByType(): array
    {
        $teacher = (float) PayrollRecord::query()
            ->join('employees', 'payroll_records.employee_id', '=', 'employees.id')
            ->where('employees.type', EmployeeType::Teacher->value)
            ->sum('payroll_records.net_salary');
        $staff = (float) PayrollRecord::query()
            ->join('employees', 'payroll_records.employee_id', '=', 'employees.id')
            ->where('employees.type', EmployeeType::Staff->value)
            ->sum('payroll_records.net_salary');
        $driver = (float) TransportService::query()->sum('driver_monthly_salary');

        return ['labels' => ['استادان', 'کارمندان', 'رانندگان'], 'values' => [$teacher, $staff, $driver]];
    }

    private function incomeBreakdown(array $range): array
    {
        return [
            'labels' => ['فیس شاگردان', 'فیس ترانسپورت', 'فروش کتاب', 'فروش یونیفورم', 'سایر فروشات'],
            'values' => [
                $this->feeIncome($range['from'], $range['to']),
                $this->transportIncome($range['from'], $range['to']),
                $this->salesByCategory('book', $range['from'], $range['to']),
                $this->salesByCategory('uniform', $range['from'], $range['to']),
                $this->salesByCategory(null, $range['from'], $range['to'], exclude: ['book', 'uniform']),
            ],
        ];
    }

    private function unpaidFeesByClass(): array
    {
        $rows = FeePayment::query()
            ->join('students', 'fee_payments.student_id', '=', 'students.id')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('SUM(fee_payments.remaining_amount) as total'))
            ->groupBy('classes.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            $rows->pluck('name')->map(fn ($value) => $value ?: 'بدون صنف')->all(),
            [$this->dataset('فیس پرداخت‌نشده', $rows->pluck('total')->all(), '#f59e0b')],
        ];
    }

    private function studentsByClass(): array
    {
        $rows = Student::query()
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('COUNT(students.id) as total'))
            ->groupBy('classes.name')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        return [
            $rows->pluck('name')->map(fn ($value) => $value ?: 'بدون صنف')->all(),
            [$this->dataset('شاگردان', $rows->pluck('total')->all(), '#6366f1')],
        ];
    }

    private function marksAverageByClass(): array
    {
        $rows = StudentResult::query()
            ->leftJoin('classes', 'student_results.class_id', '=', 'classes.id')
            ->select('classes.name', DB::raw('AVG(student_results.marks_obtained) as average_marks'))
            ->groupBy('classes.name')
            ->orderByDesc('average_marks')
            ->limit(12)
            ->get();

        return [
            $rows->pluck('name')->map(fn ($value) => $value ?: 'بدون صنف')->all(),
            [$this->dataset('میانگین', $rows->pluck('average_marks')->all(), '#10b981')],
        ];
    }

    private function passFailSummary(): array
    {
        $pass = StudentResult::query()->whereRaw('marks_obtained >= total_marks * 0.5')->count();
        $fail = StudentResult::query()->whereRaw('marks_obtained < total_marks * 0.5')->count();

        return ['labels' => ['کامیاب', 'ناکام'], 'values' => [$pass, $fail]];
    }

    private function studentAverageMarks(string $direction): array
    {
        $rows = StudentResult::query()
            ->join('students', 'student_results.student_id', '=', 'students.id')
            ->select('students.first_name', 'students.last_name', DB::raw('AVG(student_results.marks_obtained) as average_marks'))
            ->groupBy('students.id', 'students.first_name', 'students.last_name')
            ->orderBy('average_marks', $direction)
            ->limit(10)
            ->get();

        return [
            $rows->map(fn ($row) => trim($row->first_name.' '.$row->last_name))->all(),
            [$this->dataset('میانگین', $rows->pluck('average_marks')->all(), $direction === 'desc' ? '#10b981' : '#ef4444')],
        ];
    }

    private function attendanceStatusCounts(Carbon $from, Carbon $to, string $personType): array
    {
        return [
            'labels' => ['حاضر', 'غیر حاضر', 'ناوقت', 'رخصت'],
            'values' => [
                AttendanceSummary::query()->where('person_type', $personType)->whereBetween('date', [$from->toDateString(), $to->toDateString()])->where('status', AttendanceStatus::Present->value)->count(),
                AttendanceSummary::query()->where('person_type', $personType)->whereBetween('date', [$from->toDateString(), $to->toDateString()])->where('status', AttendanceStatus::Absent->value)->count(),
                AttendanceSummary::query()->where('person_type', $personType)->whereBetween('date', [$from->toDateString(), $to->toDateString()])->where('status', AttendanceStatus::Late->value)->count(),
                AttendanceSummary::query()->where('person_type', $personType)->whereBetween('date', [$from->toDateString(), $to->toDateString()])->where('status', AttendanceStatus::Excused->value)->count(),
            ],
        ];
    }

    private function teacherAttendanceSummary(array $range): array
    {
        $teacherIds = Employee::query()->where('type', EmployeeType::Teacher->value)->pluck('id');

        return [
            'labels' => ['حاضر', 'غیر حاضر', 'ناوقت', 'رخصت'],
            'values' => [
                AttendanceSummary::query()->where('person_type', Employee::class)->whereIn('person_id', $teacherIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->where('status', AttendanceStatus::Present->value)->count(),
                AttendanceSummary::query()->where('person_type', Employee::class)->whereIn('person_id', $teacherIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->where('status', AttendanceStatus::Absent->value)->count(),
                AttendanceSummary::query()->where('person_type', Employee::class)->whereIn('person_id', $teacherIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->where('status', AttendanceStatus::Late->value)->count(),
                AttendanceSummary::query()->where('person_type', Employee::class)->whereIn('person_id', $teacherIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->where('status', AttendanceStatus::Excused->value)->count(),
            ],
        ];
    }

    private function classAttendancePercentage(array $range): array
    {
        $classes = AcademicClass::query()->withCount('students')->limit(10)->get();
        $labels = [];
        $values = [];

        foreach ($classes as $class) {
            $studentIds = Student::query()->where('class_id', $class->id)->pluck('id');
            $total = AttendanceSummary::query()->where('person_type', Student::class)->whereIn('person_id', $studentIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->count();
            $present = AttendanceSummary::query()->where('person_type', Student::class)->whereIn('person_id', $studentIds)->whereBetween('date', [$range['from']->toDateString(), $range['to']->toDateString()])->where('status', AttendanceStatus::Present->value)->count();

            $labels[] = $class->name;
            $values[] = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        }

        return [$labels, [$this->dataset('فیصدی حاضری', $values, '#10b981')]];
    }

    private function biometricLogsByDevice(): array
    {
        $rows = BiometricDevice::query()
            ->withCount('logs')
            ->orderByDesc('logs_count')
            ->limit(10)
            ->get();

        return [
            $rows->pluck('name')->all(),
            [$this->dataset('لاگ‌ها', $rows->pluck('logs_count')->all(), '#6366f1')],
        ];
    }

    private function studentsByRoute(bool $activeOnly = false): array
    {
        $query = StudentTransport::query()
            ->join('transport_services', 'student_transport.transport_service_id', '=', 'transport_services.id')
            ->select('transport_services.route_name', DB::raw('COUNT(student_transport.id) as total'))
            ->groupBy('transport_services.route_name')
            ->orderByDesc('total')
            ->limit(10);

        if ($activeOnly) {
            $query->where('student_transport.status', 'active');
        }

        $rows = $query->get();

        return [
            $rows->pluck('route_name')->map(fn ($value) => $value ?: 'بدون مسیر')->all(),
            [$this->dataset('شاگردان', $rows->pluck('total')->all(), '#f59e0b')],
        ];
    }

    private function vehicleUsage(): array
    {
        $rows = TransportService::query()->withCount('assignments')->orderByDesc('assignments_count')->limit(10)->get();

        return [
            $rows->pluck('vehicle_plate_number')->map(fn ($value) => $value ?: 'موتر')->all(),
            [$this->dataset('شاگردان', $rows->pluck('assignments_count')->all(), '#0ea5e9')],
        ];
    }

    private function driverSalaryCost(): array
    {
        $rows = TransportService::query()->whereNotNull('driver_name')->orderByDesc('driver_monthly_salary')->limit(10)->get();

        return [
            $rows->pluck('driver_name')->all(),
            [$this->dataset('معاش ماهانه', $rows->pluck('driver_monthly_salary')->all(), '#ef4444')],
        ];
    }

    private function salesByCategory(?string $category, ?Carbon $from = null, ?Carbon $to = null, array $exclude = []): float
    {
        $query = StudentSaleItem::query()
            ->join('sale_items', 'student_sale_items.sale_item_id', '=', 'sale_items.id')
            ->join('student_sales', 'student_sale_items.student_sale_id', '=', 'student_sales.id');

        if ($category) {
            $query->where('sale_items.category', $category);
        }

        if ($exclude) {
            $query->whereNotIn('sale_items.category', $exclude);
        }

        if ($from && $to) {
            $query->whereBetween('student_sales.sold_at', [$from->toDateString(), $to->toDateString()]);
        }

        return (float) $query->sum('student_sale_items.total_price');
    }

    private function topSellingItems(): array
    {
        $rows = StudentSaleItem::query()
            ->join('sale_items', 'student_sale_items.sale_item_id', '=', 'sale_items.id')
            ->select('sale_items.name', DB::raw('SUM(student_sale_items.quantity) as total'))
            ->groupBy('sale_items.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            $rows->pluck('name')->all(),
            [$this->dataset('فروش', $rows->pluck('total')->all(), '#10b981')],
        ];
    }

    private function lowStockItemsCount(?string $category = null): int
    {
        $query = DB::table('sale_items')->whereColumn('stock_quantity', '<=', 'reorder_level');

        if ($category) {
            $query->where('category', $category);
        }

        return $query->count();
    }

    private function studentName(?Student $student): string
    {
        if (! $student) {
            return 'شاگرد نامشخص';
        }

        $name = trim(($student->first_name ?? '').' '.($student->last_name ?? ''));

        return $name ?: ($student->student_id ?? 'شاگرد');
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2).' AFN';
    }
}
