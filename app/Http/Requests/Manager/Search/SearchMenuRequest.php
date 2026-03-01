<?php

namespace App\Http\Requests\Manager\Search;

use App\Enum\ActiveFlagTypeEnum;
use App\Enum\MenuTypeEnum;
use App\Exceptions\InertiaValidationException;
use App\UseCases\Manager\Menu\FetchMenuListUseCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SearchMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['nullable', 'string'],
            'types'      => ['nullable', 'array'],
            'types.*'    => ['nullable', Rule::enum(MenuTypeEnum::class)],
            'shopIds'    => ['nullable', 'array'],
            'shopIds.*'  => ['nullable', 'exists:shops,id'],
            'activeFlag' => ['nullable', Rule::enum(ActiveFlagTypeEnum::class)],
            'perPage'    => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'メニュー名',
            'types'      => '種別',
            'types.*'    => '種別',
            'shopIds'    => '店舗ID',
            'shopIds.*'  => '店舗ID',
            'activeFlag' => '公開状態',
            'perPage'    => '表示件数',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = app(FetchMenuListUseCase::class)($this->safeFilters());
        $response = Inertia::render('Menu/MenuIndex', [
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
                'name'       => null,
                'types'      => null,
                'shopIds'    => null,
                'activeFlag' => null,
                'perPage'    => null,
            ],
            $this->only(['name', 'types', 'shopIds', 'activeFlag', 'perPage'])
        );
    }
}
