<?php

namespace App\Enums;

enum PerfumeGender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case UNISEX = 'unisex';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Muški',
            self::FEMALE => 'Ženski',
            self::UNISEX => 'Unisex',
        };
    }
}