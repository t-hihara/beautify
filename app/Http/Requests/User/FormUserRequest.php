<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lastName'  => ['required', 'string', 'max:20'],
            'firstName' => ['required', 'string', 'max:20'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
        ];
    }

    public function attributes(): array
    {
        return [
            'lastName'  => '姓',
            'firstName' => '名',
            'email'     => 'メールアドレス',
            'password'  => 'パスワード',
        ];
    }
}
