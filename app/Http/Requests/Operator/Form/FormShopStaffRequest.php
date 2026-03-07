<?php

namespace App\Http\Requests\Operator\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FormShopStaffRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if (!$this->route('staff')) {
            $this->merge([
                'password' => Str::random(16),
            ]);
        }
    }

    public function rules(): array
    {
        $isEdit = $this->route('staff');

        $rules = [
            'lastName'        => ['required', 'string'],
            'firstName'       => ['required', 'string'],
            'email'           => ['required', 'email', Rule::unique('shop_staff', 'email')->ignore($this->route('staff'))],
            'position'        => ['required', Rule::enum(ShopStaffPositionTypeEnum::class)],
            'experienceYears' => ['required', 'integer', 'min:1'],
            'description'     => ['nullable', 'string'],
            'activeFlag'      => ['required', Rule::enum(ActiveFlagTypeEnum::class)],
            'image'           => [
                'nullable',
                Rule::when(
                    fn() => $this->file('image') instanceof UploadedFile,
                    ['file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                    ['string'],
                ),
            ],
        ];

        if (!$isEdit) {
            $rules['shopId']   = ['required', Rule::exists('shops', 'id')];
            $rules['password'] = ['required', 'string'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'lastName'        => 'スタッフ名(姓)',
            'firstName'       => 'スタッフ名(名)',
            'shopId'          => '所属店舗',
            'email'           => 'メールアドレス',
            'position'        => 'ポジション',
            'experienceYears' => '経歴年数',
            'activeFlag'      => '有効状態',
            'description'     => 'スタッフ紹介',
            'image'           => 'プロフィール画像',
        ];
    }
}
