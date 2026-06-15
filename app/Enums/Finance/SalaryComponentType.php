<?php

namespace App\Enums\Finance;

enum SalaryComponentType: string
{
    case Allowance = 'allowance';
    case Deduction = 'deduction';
}
