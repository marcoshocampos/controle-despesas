<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDespesaRequest extends FormRequest
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
            'descricao' => 'required|string|max:191',
            'valor' => 'required|numeric|min:0',
            'data_ocorrencia' => 'required|date|before_or_equal:today',
        ];
    }
}
