<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum ActiveFlagTypeEnum: string
{
    use HasOptions;

    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE   => '有効',
            self::INACTIVE => '無効',
        };
    }
}
