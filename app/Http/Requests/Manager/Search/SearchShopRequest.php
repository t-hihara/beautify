<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['nullable', 'string'],
            'email'      => ['nullable', 'email'],
            'phone'      => ['nullable', 'string'],
            'activeFlag' => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => '店舗名',
            'email'      => 'メールアドレス',
            'phone'      => '電話番号',
            'activeFlag' => '運営状態',
        ];
    }
}
