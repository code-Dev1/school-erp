<?php

namespace App\Enums\Biometric;

enum BiometricDeviceStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Maintenance = 'maintenance';
}
