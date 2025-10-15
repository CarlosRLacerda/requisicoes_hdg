<?php

namespace App\Http\Requests;

use App\Enums\NeedEnum;
use Illuminate\Foundation\Http\FormRequest;

class SolicitarItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $necessidadeCases = array_column(NeedEnum::cases(), 'value');
        return [
            // 'necessidade' => ['required', 'string', ['in' => $necessidadeCases]],
            'qtd' => 'required|integer',
            'setor' => ['required', 'string'],

        ];
    }
}
