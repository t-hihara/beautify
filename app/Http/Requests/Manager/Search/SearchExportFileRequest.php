<?php

namespace App\Http\Requests\Manager\Search;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SearchExportFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject'  => ['nullable', 'string'],
            'fromDate' => ['nullable', 'date'],
            'toDate'   => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'subject'  => '対象名',
            'fromDate' => '作成日(from)',
            'toDate'   => '作成日(to)',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $from = $this->input('from');
                $to   = $this->input('to');
                if ($from || $to) return;

                if (Carbon::parse($to)->gt(Carbon::parse($from)->addMonth())) {
                    $validator->errors()->add('toDate', '作成日(to)は、作成日(from)から1ヶ月以内にしてください。');
                }
            }
        ];
    }
}
