<?php

namespace App\Http\Requests\Manager\Search;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'name'     => ['nullable', 'string'],
            'event'    => ['nullable', 'string'],
            'fromDate' => ['required', 'date'],
            'toDate'   => ['nullable', 'date'],
            'perPage'  => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => '実行者名',
            'event'    => 'アクション名',
            'fromDate' => '作成日(開始)',
            'toDate'   => '作成日(終了)',
            'perPage'  => '表示件数',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $from = $this->input('fromDate');
                $to = $this->input('toDate');
                if (!$from || !$to) {
                    return;
                }

                if (Carbon::parse($to)->gt(Carbon::parse($from)->addMonth())) {
                    $validator->errors()->add('toDate', '作成日(終了)は、作成日(開始)から1ヶ月以内に設定してください。');
                }
            }
        ];
    }
}
