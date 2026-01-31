<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum GenderTypeEnum: string
{
    use HasOptions;

    case MALE   = 'male';
    case FEMALE = 'female';
    case OTHER  = "other";

    public function description(): string
    {
        return match ($this) {
            self::MALE   => '男性',
            self::FEMALE => '女性',
            self::OTHER  => 'その他',
        };
    }
}
