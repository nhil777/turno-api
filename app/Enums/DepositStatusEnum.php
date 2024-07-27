<?php

namespace App\Enums;

use App\Helpers\EnumHelper;

enum DepositStatusEnum: string
{
    use EnumHelper;

    case WAITING_APPROVAL = 'waiting_approval';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
