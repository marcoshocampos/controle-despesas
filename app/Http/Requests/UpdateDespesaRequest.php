<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDespesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao' => 'sometimes|string|max:191',
            'valor' => 'sometimes|numeric|min:0',
            'data_ocorrencia' => 'sometimes|date|before_or_equal:today',
        ];
    }
}
