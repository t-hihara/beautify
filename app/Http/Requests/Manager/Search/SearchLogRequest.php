<?php

namespace App\Http\Requests\Manager\Search;

use App\Exceptions\InertiaValidationException;
use App\UseCases\Manager\Log\FetchLogListUseCase;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Inertia\Inertia;

class SearchLogRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if (!request()->has('fromDate')) {
            $this->merge([
                'fromDate' => Carbon::now()->subMonth()->format('Y-m-d'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name'        => ['nullable', 'string'],
            'event'       => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'fromDate'    => ['required', 'date'],
            'toDate'      => ['nullable', 'date', 'after_or_equal:fromDate'],
            'perPage'     => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => '実行者名',
            'event'       => 'アクション名',
            'description' => '内容',
            'fromDate'    => '作成日(from)',
            'toDate'      => '作成日(to)',
            'perPage'     => '表示件数',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $from = $this->input('fromDate');
                $to   = $this->input('toDate');
                if (!$from || !$to) return;

                if (Carbon::parse($to)->gt(Carbon::parse($from)->addMonth())) {
                    $validator->errors()->add('toDate', '作成日(to)は、作成日(開始)から1ヶ月以内に設定してください。');
                }
            }
        ];
    }

    protected function failedValidation(ValidationValidator $validator): void
    {
        $data = app(FetchLogListUseCase::class)($this->safeFilters());
        $response = Inertia::render('Log/LogIndex', [
            ...$data,
            'errors' => collect($validator->errors()->getMessages())
                ->map(fn(array $messages) => $messages[0] ?? '')
                ->toArray(),
        ]);

        throw new InertiaValidationException($response);
    }

    private function safeFilters(): array
    {
        return array_merge(
            [
                'fromDate'    => Carbon::now()->subMonth()->format('Y-m-d'),
                'toDate'      => null,
                'name'        => null,
                'event'       => null,
                'description' => null,
                'perPage'     => 20,
            ],
            $this->only(['fromDate', 'toDate', 'name', 'event', 'description', 'perPage'])
        );
    }
}
