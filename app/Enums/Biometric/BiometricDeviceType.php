<?php

namespace App\Enums\Biometric;

enum BiometricDeviceType: string
{
    case Zkteco = 'zkteco';
    case Other = 'other';
}
