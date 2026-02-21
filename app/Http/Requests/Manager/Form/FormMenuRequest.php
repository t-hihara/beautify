<?php

namespace App\Http\Requests\Manager\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormMenuRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name'        => ['required', 'string', 'max:20'],
            'type'        => ['required', Rule::enum(MenuTypeEnum::class)],
            'price'       => ['required', 'integer', 'min:1'],
            'duration'    => ['required', 'integer', 'min:5', 'max:300'],
            'description' => ['nullable', 'string'],
            'activeFlag'  => ['required', Rule::enum(ActiveFlagTypeEnum::class)],
        ];

        if (!$this->route('menu')) {
            $rules['shopId'] = ['required', 'integer', 'exists:shops,id'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name'        => 'メニュー名',
            'shopId'      => '店舗',
            'type'        => 'メニュー種別',
            'price'       => '料金',
            'duration'    => '所要時間',
            'description' => 'メニュー説明',
            'activeFlag'  => '公開状態',
        ];
    }
}
