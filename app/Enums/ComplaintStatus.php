<?php

namespace App\Enums;

class ComplaintStatus
{
    public const WAITING = 'waiting';

    public const UNDER_REVIEW = 'under_review';

    public const ON_PROCESS = 'on_process';

    public const SOLVED = 'solved';

    public const REJECTED = 'rejected';
}