<?php

namespace App\Enums\Students;

enum StudentStatus: string
{
    case Active = 'active';
    case Transferred = 'transferred';
    case Graduated = 'graduated';
    case Expelled = 'expelled';
}
