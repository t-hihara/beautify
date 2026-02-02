<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class FormResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token'                => ['required', 'string'],
            'email'                => ['required', 'email'],
            'password'             => ['required', 'string'],
            'passwordConfirmation' => ['required', 'string', 'same:password'],
        ];
    }

    public function attributes(): array
    {
        return [
            'token'                => 'トークン',
            'email'                => 'メールアドレス',
            'password'             => 'パスワード',
            'passwordConfirmation' => 'パスワード(確認用)',
        ];
    }
}
