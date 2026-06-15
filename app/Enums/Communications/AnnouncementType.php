<?php

namespace App\Enums\Communications;

enum AnnouncementType: string
{
    case General = 'general';
    case Absence = 'absence';
    case Fee = 'fee';
}
