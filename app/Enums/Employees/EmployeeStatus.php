<?php

namespace App\Enums\Employees;

enum EmployeeStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Resigned = 'resigned';
    case Terminated = 'terminated';
}
