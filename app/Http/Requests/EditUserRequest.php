<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userRule = 'sometimes';
        return [
            'name' => [$userRule, 'string'],
            'email' => [$userRule, 'string'],
            'password' => [$userRule, 'string'],
            'role' => [$userRule, ['in' => ['admin', 'default', 'almo']]],
        ];
    }
}
