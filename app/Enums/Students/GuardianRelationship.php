<?php

namespace App\Enums\Students;

enum GuardianRelationship: string
{
    case Father = 'father';
    case Mother = 'mother';
    case Uncle = 'uncle';
    case Brother = 'brother';
    case Guardian = 'guardian';
}
