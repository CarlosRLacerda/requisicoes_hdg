<?php

namespace App\Http\Requests;

use App\Enums\StatusRequestEnum;
use Illuminate\Foundation\Http\FormRequest;

class RequestsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusCases = array_column(StatusRequestEnum::cases(), 'value');
        $statusRole = $this->getMethod() == 'POST' ? 'required' : 'sometimes';

        return [

            'status' => [$statusRole, 'string', ['in' => $statusCases]]
        ];
    }
}
