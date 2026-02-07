<?php

namespace App\Enum;

use App\Enum\Traits\HasOptions;

enum ExportFileStatusTypeEnum: string
{
    use HasOptions;

    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case FAILED     = 'failed';

    public function description(): string
    {
        return match ($this) {
            self::PENDING    => '準備中',
            self::PROCESSING => '処理中',
            self::COMPLETED  => '完了',
            self::FAILED     => '失敗',
        };
    }
}
