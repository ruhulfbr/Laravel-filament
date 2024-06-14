<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: int implements HasColor, HasLabel
{
    case ADMIN = 1;
    case USER = 0;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::USER => 'User'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ADMIN => 'success',
            self::USER => 'info'
        };
    }
}
