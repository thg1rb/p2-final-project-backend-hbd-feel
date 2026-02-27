<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case SUBMITTED = "SUBMITTED";
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}
