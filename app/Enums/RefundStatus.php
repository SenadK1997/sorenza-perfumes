<?php

namespace App\Enums;

enum RefundStatus: string
{
    case PENDING  = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'Na čekanju',
            self::APPROVED => 'Odobreno',
            self::REJECTED => 'Odbijeno',
            self::REFUNDED => 'Vraćen novac',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING  => 'warning',
            self::APPROVED => 'info',
            self::REJECTED => 'danger',
            self::REFUNDED => 'success',
        };
    }
}
