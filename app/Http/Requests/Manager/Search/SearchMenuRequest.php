<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['nullable', 'string'],
            'types'      => ['nullable', 'array'],
            'types.*'    => ['nullable', Rule::enum(MenuTypeEnum::class)],
            'shopIds'    => ['nullable', 'array'],
            'shopIds.*'  => ['nullable', 'exists:shops,id'],
            'activeFlag' => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'メニュー名',
            'types'      => '種別',
            'types.*'    => '種別',
            'shopIds'    => '店舗ID',
            'shopIds.*'  => '店舗ID',
            'activeFlag' => '公開状態',
        ];
    }
}
