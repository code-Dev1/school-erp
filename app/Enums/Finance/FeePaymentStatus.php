<?php

namespace App\Enums\Finance;

enum FeePaymentStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Partial = 'partial';
    case Exempted = 'exempted';
}
