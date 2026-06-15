<?php

namespace App\Enums\Employees;

enum ContractType: string
{
    case Permanent = 'permanent';
    case Contract = 'contract';
    case Hourly = 'hourly';
}
