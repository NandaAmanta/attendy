<?php

namespace App\Consts;

enum LeaveStatus: string
{
    case DRAFT = 'draft';
    case WAITING_FOR_APPROVAL = 'waiting_for_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
