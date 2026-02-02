<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FormForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}
