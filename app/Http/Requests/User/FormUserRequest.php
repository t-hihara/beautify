<?php

namespace App\Http\Requests\User;

use App\Enum\GenderTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormUserRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'user.lastName'     => ['required', 'string', 'max:20'],
            'user.firstName'    => ['required', 'string', 'max:20'],
            'user.email'        => ['required', 'email', 'unique:users,email'],
            'user.password'     => ['required', 'string', 'min:8'],
            'customer.name'     => ['required', 'string', 'max:20'],
            'customer.nameKana' => ['required', 'string', 'max:50'],
            'customer.email'    => ['required', 'email'],
            'customer.phone'    => ['required', 'string', 'regex:/^\d{2,4}-\d{2,4}-\d{4}$/'],
            'customer.dob'      => ['required', 'date'],
            'customer.gender'   => ['required', Rule::enum(GenderTypeEnum::class)],
        ];

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'user.lastName'     => '姓',
            'user.firstName'    => '名',
            'user.email'        => 'メールアドレス',
            'user.password'     => 'パスワード',
            'customer.name'     => '顧客名',
            'customer.nameKana' => '顧客名カナ',
            'customer.email'    => 'メールアドレス',
            'customer.phone'    => '電話番号',
            'customer.dob'      => '生年月日',
            'customer.gender'   => '性別',
        ];
    }
}
