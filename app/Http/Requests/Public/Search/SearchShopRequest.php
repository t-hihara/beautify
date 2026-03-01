<?php

namespace App\Http\Requests\Public\Search;

use App\Exceptions\InertiaValidationException;
use App\UseCases\Public\FetchShopsUseCase;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Inertia\Inertia;

class SearchShopRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'prefectures' => $this->parseCommaSeparatedIntegers('prefectures'),
            'areas'       => $this->parseCommaSeparatedIntegers('areas'),
        ]);
    }

    public function rules(): array
    {
        return [
            'date'          => ['nullable', 'date'],
            'prefectures'   => ['nullable', 'array'],
            'prefectures.*' => ['nullable', 'integer', 'exists:prefectures,id'],
            'areas'         => ['nullable', 'array'],
            'areas.*'       => ['nullable', 'integer', 'exists:areas,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date'          => '選択日付',
            'prefectures'   => '都道府県',
            'prefectures.*' => '都道府県',
            'areas'         => 'エリア',
            'areas.*'       => 'エリア',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = app(FetchShopsUseCase::class)($this->safeFilters());
        $response = Inertia::render('Public/ShopList', [
            ...$data,
            'errors' => collect($validator->errors()->getMessages())
                ->map(fn(array $messages) => $messages[0] ?? '')
                ->toArray(),
        ]);

        throw new InertiaValidationException($response);
    }

    private function safeFilters(): array
    {
        return array_merge([
            'date'        => null,
            'prefectures' => null,
            'areas'       => null,
        ], $this->only(['date', 'prefectures', 'areas']));
    }

    private function parseCommaSeparatedIntegers(string $field): array
    {
        if (!$this->has($field) || $this->input($field) === '') {
            return [];
        }

        return collect(explode(',', $this->input($field)))
            ->filter()
            ->map('intval')
            ->toArray();
    }
}
