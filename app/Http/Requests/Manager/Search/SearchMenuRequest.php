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
            'name'        => ['nullable', 'string'],
            'menuTypes'   => ['nullable', 'array'],
            'menuTypes.*' => ['nullable', Rule::enum(MenuTypeEnum::class)],
            'activeFlag'  => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'メニュー名',
            'menuTypes'   => '種別',
            'menuTypes.*' => '種別',
            'activeFlag'  => '公開状態',
        ];
    }
}
