<?php

namespace App\Http\Requests\Manager\Search;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SearchLogRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if (!request()->filled('fromDate')) {
            $this->merge([
                'fromDate' => Carbon::now()->format('Y-m-d'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name'     => ['nullable', 'string'],
            'event'    => ['nullable', 'string'],
            'fromDate' => ['nullable', 'date'],
            'toDate'   => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => '実行者名',
            'event'    => 'アクション名',
            'fromDate' => '作成日(開始)',
            'toDate'   => '作成日(終了)'
        ];
    }
}
