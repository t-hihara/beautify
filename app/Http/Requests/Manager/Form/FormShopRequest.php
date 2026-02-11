<?php

namespace App\Http\Requests\Manager\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\DayOfWeekTypeEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class FormShopRequest extends FormRequest
{
    public function rules(): array
    {
        $isEdit = $this->route('shop');
        $rules = [
            'shop.name'                      => ['required', 'string', 'max:20'],
            'shop.email'                     => ['required', 'email', Rule::unique('shops', 'email')->ignore($this->route('shop'))],
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
            'shop.businessHours.*.openTime'  => ['nullable', 'required_with:shop.businessHours.*.closeTime', 'string'],
            'shop.businessHours.*.closeTime' => ['nullable', 'required_with:shop.businessHours.*.openTime', 'string'],
            'shop.newImages'                 => ['nullable', 'array'],
            'shop.newImages.*'               => ['file', 'image', 'max:2048'],
        ];

        if ($isEdit) {
            $rules['shop.keepImageIds']   = ['nullable', 'array'];
            $rules['shop.keepImageIds.*'] = ['integer', Rule::exists('shop_images', 'id')->where('shop_id', $this->route('shop')->id)];
        }

        return $rules;
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
            'shop.businessHours.*.openTime'  => '開店時間',
            'shop.businessHours.*.closeTime' => '閉店時間',
            'shop.keepImageIds'              => '店舗画像(既存)',
            'shop.newImages'                 => '店舗画像(新規)',
            'shop.newImages.*'               => '店舗画像(新規)',
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator)  => $this->validateBusinessHours($validator),
            fn(Validator $validator) => $this->validateShopImageCount($validator),
        ];
    }

    private function validateBusinessHours(Validator $validator): void
    {
        $businessHours = $this->input('shop.businessHours', []);
        foreach ($businessHours as $index => $businessHour) {
            $openTime  = $businessHour['openTime'] ?? null;
            $closeTime = $businessHour['closeTime'] ?? null;
            if ($closeTime === null && $openTime === null) continue;

            $open  = Carbon::parse($openTime);
            $close = Carbon::parse($closeTime);

            if ($close->lt($open)) {
                $validator->errors()->add(
                    "shop.businessHours.{$index}.closeTime",
                    '閉店時間は開店時間以降に設定してください。',
                );
            }
        }
    }

    private function validateShopImageCount(Validator $validator): void
    {
        $keep  = $this->input('shop.keepImageIds', []);
        $new   = $this->file('shop.newImages', []);
        $total = count($keep) + count($new);

        if ($total < 1 || $total > 5) {
            $validator->errors()->add(
                'shop.keepImageIds',
                '画像は1枚以上5枚以下にしてください。',
            );
        }
    }
}
