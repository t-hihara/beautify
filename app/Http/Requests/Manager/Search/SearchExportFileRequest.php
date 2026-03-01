<?php

namespace App\Http\Requests\Manager\Search;

use App\Exceptions\InertiaValidationException;
use App\UseCases\Manager\ExportFile\FetchExportFileListUseCase;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Inertia\Inertia;

class SearchExportFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject'  => ['nullable', 'string'],
            'fromDate' => ['nullable', 'date'],
            'toDate'   => ['nullable', 'date'],
            'perPage'  => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'subject'  => '対象名',
            'fromDate' => '作成日(from)',
            'toDate'   => '作成日(to)',
            'perPage'  => '表示件数',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $from = $this->input('fromDate');
                $to   = $this->input('toDate');
                if (!$from || !$to) {
                    return;
                }

                if (Carbon::parse($to)->gt(Carbon::parse($from)->addMonth())) {
                    $validator->errors()->add('toDate', '作成日(to)は、作成日(from)から1ヶ月以内にしてください。');
                }
            }
        ];
    }

    protected function failedValidation(ValidationValidator $validator): void
    {
        $userId = auth($this->attributes->get('auth_guard'))->id();
        $data   = app(FetchExportFileListUseCase::class)($this->safeFilters(), $userId);
        $response = Inertia::render('Export/ExportFileList', [
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
                'subject'  => null,
                'fromDate' => null,
                'toDate'   => null,
                'perPage'  => null,
            ],
            $this->only(['subject', 'fromDate', 'toDate', 'perPage'])
        );
    }
}
