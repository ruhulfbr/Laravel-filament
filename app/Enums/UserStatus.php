<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum UserStatus: int implements hasLabel, hasColor, hasIcon
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'warning'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-shield-check',
            self::INACTIVE => 'heroicon-o-shield-exclamation'
        };
    }
}
