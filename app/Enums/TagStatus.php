<?php

namespace App\Enums;

enum TagStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getText(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive'
        };
    }
}
