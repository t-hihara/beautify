<?php

namespace Database\Seeders\Definitions;

use App\Enum\MenuTypeEnum;
use App\Enum\PlanConditionTypeEnum;

final class PlanTemplate
{
    public static function getDefinitions(): array
    {
        return [
            'cut_color' => [
                'name'           => 'カット＋カラー',
                'description'    => 'カットとカラーがセットになったお得なプランです。',
                'total_duration' => 135,
                'regular_price'  => 12000,
                'selling_price'  => 10000,
                'condition_type' => PlanConditionTypeEnum::FIRST_VISIT->value,
                'valid_from'     => null,
                'valid_to'       => null,
                'menu_types'     => [MenuTypeEnum::CUT, MenuTypeEnum::COLOR],
            ],
            'cut_color_treatment' => [
                'name'           => 'カット＋カラー＋トリートメント',
                'description'    => 'カット・カラー・トリートメントのフルセットプラン。',
                'total_duration' => 165,
                'regular_price'  => 16000,
                'selling_price'  => 13000,
                'condition_type' => PlanConditionTypeEnum::WEEKDAY->value,
                'valid_from'     => null,
                'valid_to'       => null,
                'menu_types'     => [MenuTypeEnum::CUT, MenuTypeEnum::COLOR, MenuTypeEnum::TREATMENT],
            ],
            'cut_perm' => [
                'name'           => 'カット＋パーマ',
                'description'    => 'カットとパーマのセットプラン。',
                'total_duration' => 135,
                'regular_price'  => 13000,
                'selling_price'  => 11000,
                'condition_type' => null,
                'valid_from'     => null,
                'valid_to'       => null,
                'menu_types'     => [MenuTypeEnum::CUT, MenuTypeEnum::PERM],
            ],
            'full_care' => [
                'name'           => 'フルケアプラン',
                'description'    => 'カット・カラー・トリートメント・ヘッドスパの総合プラン。',
                'total_duration' => 210,
                'regular_price'  => 22000,
                'selling_price'  => 18000,
                'condition_type' => PlanConditionTypeEnum::PERIOD->value,
                'valid_from'     => now()->copy()->startOfMonth()->format('Y-m-d'),
                'valid_to'       => now()->copy()->endOfMonth()->format('Y-m-d'),
                'menu_types'     => [MenuTypeEnum::CUT, MenuTypeEnum::COLOR, MenuTypeEnum::TREATMENT, MenuTypeEnum::HEAD_SPA],
            ],
        ];
    }
}
