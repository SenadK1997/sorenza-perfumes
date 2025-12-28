<?php

namespace App\Enums;

enum DebtStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
}