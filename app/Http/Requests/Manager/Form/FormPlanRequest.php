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
        $rules = [
            'name'          => ['required', 'string'],
            'description'   => ['required', 'string'],
            'duration'      => ['required', 'integer', 'min:1'],
            'regularPrice'  => ['required', 'integer', 'min:1'],
            'sellingPrice'  => ['required', 'integer', 'min:1'],
            'conditionType' => ['nullable', Rule::enum(PlanConditionTypeEnum::class)],
            'activeFlag'    => ['required', Rule::enum(ActiveFlagTypeEnum::class)],
            'sortOrder'     => ['required', 'integer', 'min:1'],
            'validFrom'     => ['nullable', 'required_if:conditionType,' . PlanConditionTypeEnum::PERIOD->value, 'date'],
            'validTo'       => ['nullable', 'required_if:conditionType,' . PlanConditionTypeEnum::PERIOD->value, 'date', 'after:validFrom'],
            'menuIds'       => ['required', 'array'],
            'menuIds.*'     => ['required', 'integer', 'exists:menus,id'],
        ];

        if ($this->route('plan')) {
            $rules['image'] = ['nullable', Rule::when(
                fn() => $this->file('image') instanceof UploadedFile,
                ['file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                ['string'],
            )];
        } else {
            $rules['shopId'] = ['required', 'integer', 'exists:shops,id'];
            $rules['image']  = ['required', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'shopId'        => '店舗',
            'name'          => 'プラン名',
            'description'   => 'プラン説明',
            'duration'      => '所要時間',
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

    public function messages(): array
    {
        return [
            'validFrom.required_if' => ':attributeは適用条件が期間限定の時に設定が必要です。',
            'validTo.required_if'   => ':attributeは適用条件が期間限定の時に設定が必要です。',
        ];
    }
}
