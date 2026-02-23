<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['nullable', 'string'],
            'activeFlag' => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'shopIds'    => ['nullable', 'array'],
            'shopIds.*'  => ['nullable', 'integer', 'exists:shops,id'],
            'types'      => ['nullable', 'array'],
            'types.*'    => ['nullable', Rule::enum(MenuTypeEnum::class)],
            'validFrom'  => ['nullable', 'date'],
            'validTo'    => ['nullable', 'date'],
            'perPage'    => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'プラン名',
            'activeFlag' => '公開状態',
            'shopIds'    => '店舗ID',
            'shopIds.*'  => '店舗ID',
            'types'      => 'メニュー種別',
            'types.*'    => 'メニュー種別',
            'validFrom'  => '期間限定（開始）',
            'validTo'    => '期間限定（終了）',
            'perPage'    => '表示件数',
        ];
    }
}
