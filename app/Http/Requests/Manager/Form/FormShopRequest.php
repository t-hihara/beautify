<?php

namespace App\Http\Requests\Manager\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\DayOfWeekTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shop.name'                      => ['required', 'string', 'max:20'],
            'shop.email'                     => ['required', 'email'],
            'shop.phone'                     => ['required', 'string',],
            'shop.prefectureId'              => ['required', 'integer', 'exists:prefectures,id'],
            'shop.zipcode'                   => ['required', 'string'],
            'shop.address'                   => ['required', 'string'],
            'shop.building'                  => ['nullable', 'string'],
            'shop.description'               => ['nullable', 'string'],
            'shop.activeFlag'                => ['required', Rule::enum(ActiveFlagTypeEnum::class)],
            'shop.businessHours'             => ['required', 'array'],
            'shop.businessHours.*.id'        => ['nullable', 'integer'],
            'shop.businessHours.*.dayOfWeek' => ['required', Rule::enum(DayOfWeekTypeEnum::class)],
            'shop.businessHours.*.openTime'  => ['nullable', 'string'],
            'shop.businessHours.*.closeTime' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'shop.name'                      => '店舗名',
            'shop.email'                     => 'メールアドレス',
            'shop.phone'                     => '電話番号',
            'shop.prefectureId'              => '都道府県',
            'shop.zipcode'                   => '郵便番号',
            'shop.address'                   => '住所',
            'shop.building'                  => '番地・建物名',
            'shop.description'               => '店舗説明',
            'shop.activeFlag'                => '運営状態',
            'shop.businessHours'             => '営業時間',
            'shop.businessHours.*.id'        => '営業時間ID',
            'shop.businessHours.*.dayOfWeek' => '曜日',
            'shop.businessHours.*.openTime'  => 'オープン時間',
            'shop.businessHours.*.closeTime' => '閉店時間',
        ];
    }
}
