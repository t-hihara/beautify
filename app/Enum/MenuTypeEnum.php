<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum MenuTypeEnum: string
{
    use HasOptions;

    case CUT           = 'cut';
    case COLOR         = 'color';
    case PERM          = 'perm';
    case TREATMENT     = 'treatment';
    case HEAD_SPA      = 'head_spa';
    case STRAIGHTENING = 'straightening';
    case HAIR_SET      = 'hair_set';
    case BANG_CUT      = 'bang_cut';
    case RETOUCH_COLOR = 'retouch_color';
    case EYEBROW_CUT   = 'eyebrow_cut';
    case SHAVING       = 'shaving';
    case MENS_CUT      = 'mens_cut';
    case KITSUKE       = 'kitsuke';

    public function description(): string
    {
        return match ($this) {
            self::CUT           => 'カット',
            self::COLOR         => 'カラー',
            self::PERM          => 'パーマ',
            self::TREATMENT     => 'トリートメント',
            self::HEAD_SPA      => 'ヘッドスパ',
            self::STRAIGHTENING => '縮毛矯正・ストレート',
            self::HAIR_SET      => 'ヘアセット・ヘアアレンジ',
            self::BANG_CUT      => '前髪カット',
            self::RETOUCH_COLOR => 'リタッチカラー',
            self::EYEBROW_CUT   => '眉カット',
            self::SHAVING       => 'シェービング',
            self::MENS_CUT      => 'メンズカット',
            self::KITSUKE       => '着付け',
        };
    }
}
