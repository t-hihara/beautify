<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum ShopStaffPositionTypeEnum: string
{
    use HasOptions;

    case MANAGER        = 'manager';
    case SALON_MANAGER  = 'salon_manager';
    case TOP_STYLIST    = 'top_stylist';
    case STYLIST        = 'stylist';
    case JUNIOR_STYLIST = 'junior_stylist';
    case ASSISTANT      = 'assistant';

    public function description(): string
    {
        return match ($this) {
            self::MANAGER        => 'マネージャー',
            self::SALON_MANAGER  => 'サロンマネージャー',
            self::TOP_STYLIST    => 'トップスタイリスト',
            self::STYLIST        => 'スタイリスト',
            self::JUNIOR_STYLIST => 'ジュニアスタイリスト',
            self::ASSISTANT      => 'アシスタント',
        };
    }
}
