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
            'name'             => ['nullable', 'string'],
            'email'            => ['nullable', 'string'],
            'phone'            => ['nullable', 'string'],
            'prefectureIds'    => ['nullable', 'array'],
            'prefecturesIds.*' => ['nullable', 'integer', 'exists:prefectures,id'],
            'activeFlag'       => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'perPage'          => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => '店舗名',
            'email'            => 'メールアドレス',
            'phone'            => '電話番号',
            'prefecturesIds'   => '都道府県',
            'prefecturesIds.*' => '都道府県名',
            'activeFlag'       => '運営状態',
            'perPage'          => '表示件数',
        ];
    }
}
