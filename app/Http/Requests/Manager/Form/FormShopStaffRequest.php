<?php

namespace App\Http\Requests\Manager\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Models\ShopStaff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class FormShopStaffRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
    }

    public function attributes(): array
    {
        return [
            'lastName'        => 'スタッフ名(姓)',
            'firstName'       => 'スタッフ名(名)',
            'email'           => 'メールアドレス',
            'position'        => 'ポジション',
            'experienceYears' => '経歴年数',
            'activeFlag'      => '有効状態',
            'description'     => 'スタッフ紹介',
            'image'           => 'プロフィール画像',
        ];
    }
}
