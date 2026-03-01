<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\ShopStaffPositionTypeEnum;
use App\Exceptions\InertiaValidationException;
use App\UseCases\Manager\ShopStaff\FetchShopStaffListUseCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SearchShopStaffRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['nullable', 'string'],
            'shopIds'     => ['nullable', 'array'],
            'shopIds.*'   => ['integer', 'exists:shops,id'],
            'activeFlag'  => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'positions'   => ['nullable', 'array'],
            'positions.*' => ['string', Rule::enum(ShopStaffPositionTypeEnum::class)],
            'perPage'     => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'スタッフ名',
            'shopIds'     => '店舗ID',
            'shopIds.*'   => '店舗ID',
            'activeFlag'  => '有効状態',
            'positions'   => 'ポジション',
            'positions.*' => 'ポジション',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = app(FetchShopStaffListUseCase::class)($this->safeFilters());
        $response = Inertia::render('ShopStaff/ShopStaffList', [
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
            'shopIds'    => null,
            'activeFlag' => null,
            'positions'  => null,
            'perPage'    => null,
        ], $this->only(['name', 'shopIds', 'activeFlag', 'positions', 'perPage']));
    }
}
