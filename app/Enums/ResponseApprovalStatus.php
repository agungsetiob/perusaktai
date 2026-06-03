<?php

namespace App\Enums;

enum ResponseApprovalStatus: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';

    case REJECTED = 'rejected';
}