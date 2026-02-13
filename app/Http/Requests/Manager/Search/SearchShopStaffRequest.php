<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchShopStaffRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['nullable', 'string'],
            'shopIds'     => ['nullable', 'array'],
            'shopIds.*'   => ['integer', 'exists:shops,id'],
            'activeFlag'  => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'positions'   => ['nullable', 'array'],
            'positions.*' => ['string', Rule::enum(ShopStaffPositionTypeEnum::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'スタッフ名',
            'shopIds'     => '店舗ID',
            'shopIds.*'   => '店舗ID',
            'activeFlag'  => '有効状態',
            'positions'   => 'ポジション',
            'positions.*' => 'ポジション',
        ];
    }
}
