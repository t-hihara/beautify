<?php

namespace App\Http\Requests\Operator\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Exceptions\InertiaValidationException;
use App\UseCases\Operator\Shop\FetchShopListUseCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SearchShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'            => ['nullable', 'string'],
            'email'           => ['nullable', 'string'],
            'phone'           => ['nullable', 'string'],
            'prefectureIds'   => ['nullable', 'array'],
            'prefectureIds.*' => ['nullable', 'integer', 'exists:prefectures,id'],
            'activeFlag'      => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'perPage'         => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => '店舗名',
            'email'           => 'メールアドレス',
            'phone'           => '電話番号',
            'prefectureIds'   => '都道府県',
            'prefectureIds.*' => '都道府県名',
            'activeFlag'      => '運営状態',
            'perPage'         => '表示件数',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = app(FetchShopListUseCase::class)($this->safeFilters());
        $response = Inertia::render('Shop/ShopList', [
            ...$data,
            'errors' => collect($validator->errors()->getMessages())
                ->map(fn(array $messages) => $messages[0] ?? '')
                ->all(),
        ]);

        throw new InertiaValidationException($response);
    }

    private function safeFilters(): array
    {
        return array_merge(
            [
                'name'          => null,
                'email'         => null,
                'phone'         => null,
                'prefectureIds' => null,
                'activeFlag'    => null,
                'perPage'       => null,
            ],
            $this->only(['name', 'email', 'phone', 'prefectureIds', 'activeFlag', 'perPage'])
        );
    }
}
