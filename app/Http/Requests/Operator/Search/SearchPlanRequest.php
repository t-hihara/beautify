<?php

namespace App\Http\Requests\Operator\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Exceptions\InertiaValidationException;
use App\UseCases\Operator\Plan\FetchPlanListUseCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SearchPlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['nullable', 'string'],
            'activeFlag' => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'shopIds'    => ['nullable', 'array'],
            'shopIds.*'  => ['nullable', 'integer', 'exists:shops,id'],
            'types'      => ['nullable', 'array'],
            'types.*'    => ['nullable', Rule::enum(MenuTypeEnum::class)],
            'validFrom'  => ['nullable', 'date'],
            'validTo'    => ['nullable', 'date', 'after_or_equal:validFrom'],
            'perPage'    => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'プラン名',
            'activeFlag' => '公開状態',
            'shopIds'    => '店舗ID',
            'shopIds.*'  => '店舗ID',
            'types'      => 'メニュー種別',
            'types.*'    => 'メニュー種別',
            'validFrom'  => '期間限定（開始）',
            'validTo'    => '期間限定（終了）',
            'perPage'    => '表示件数',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = app(FetchPlanListUseCase::class)($this->safeFilters());
        $response = Inertia::render('Plan/PlanList', [
            ...$data,
            'errors' => collect($validator->errors()->getMessages())
                ->map(fn(array $messages) => $messages[0] ?? '')
                ->all(),
        ]);

        throw new InertiaValidationException($response);
    }

    private function safeFilters(): array
    {
        return array_merge([
            'name'       => null,
            'activeFlag' => null,
            'shopIds'    => null,
            'types'      => null,
            'validFrom'  => null,
            'validTo'    => null,
            'perPage'    => null,
        ], $this->only(['name', 'activeFlag', 'shopIds', 'types', 'validFrom', 'validTo', 'perPage']));
    }
}
