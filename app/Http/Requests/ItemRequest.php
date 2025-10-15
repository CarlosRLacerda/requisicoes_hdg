<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $requiredRule = $this->getMethod() === 'POST' ? 'required' : 'sometimes';

        return [
            'cod' => [$requiredRule, 'string'],
            'item' => [$requiredRule, 'string'],
            'unidade' => [$requiredRule, 'string'],
            'qtd' => [$requiredRule, 'integer'],
        ];
    }
}
