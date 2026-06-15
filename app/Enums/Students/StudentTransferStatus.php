<?php

namespace App\Enums\Students;

enum StudentTransferStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
