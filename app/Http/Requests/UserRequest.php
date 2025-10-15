<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userRule = 'required';
        return [
            'name' => [$userRule, 'string'],
            'email' => [$userRule, 'string'],
            'role' => [$userRule, ['in' => ['admin', 'default', 'almo']]],
        ];
    }
}
