<?php

namespace App\Enums\Reports;

enum ReportType: string
{
    case Students = 'students';
    case StudentAttendance = 'student_attendance';
    case TeacherAttendance = 'teacher_attendance';
    case StaffAttendance = 'staff_attendance';
    case Payroll = 'payroll';
    case Expenses = 'expenses';
}
