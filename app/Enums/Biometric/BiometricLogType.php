<?php

namespace App\Enums\Biometric;

enum BiometricLogType: string
{
    case CheckIn = 'check_in';
    case CheckOut = 'check_out';
}
