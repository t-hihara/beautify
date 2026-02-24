<?php

namespace App\Http\Requests\Manager\Form;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\PlanConditionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class FormPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string'],
            'description'   => ['required', 'string'],
            'totalDuration' => ['required', 'integer', 'min:1'],
            'regularPrice'  => ['required', 'integer', 'min:1'],
            'sellingPrice'  => ['required', 'integer', 'min:1'],
            'conditionType' => ['nullable', Rule::enum(PlanConditionTypeEnum::class)],
            'activeFlag'    => ['required', Rule::enum(ActiveFlagTypeEnum::class)],
            'sortOrder'     => ['required', 'integer', 'min:1'],
            'validFrom'     => ['nullable', 'date'],
            'validTo'       => ['nullable', 'date'],
            'image'         => [
                'nullable',
                Rule::when(
                    fn() => $this->file('image') instanceof UploadedFile,
                    ['file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                    ['string'],
                ),
            ],
            'menuIds'   => ['required', 'array'],
            'menuIds.*' => ['required', 'integer', 'exists:menus,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'プラン名',
            'description'   => 'プラン説明',
            'totalDuration' => '所要時間',
            'regularPrice'  => '通常価格',
            'sellingPrice'  => '表示価格',
            'conditionType' => '適用条件',
            'activeFlag'    => '公開状態',
            'sortOrder'     => '並び順',
            'validFrom'     => '期間限定（開始）',
            'validTo'       => '期間限定（終了）',
            'image'         => 'プラン画像',
            'menuIds'       => 'メニュー',
            'menuIds.*'     => 'メニュー',
        ];
    }
}
