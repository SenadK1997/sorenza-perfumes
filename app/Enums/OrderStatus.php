<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case TAKEN = 'taken';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::TAKEN => 'Taken',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }
}