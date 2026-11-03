<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAtivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ticker' => ['required', 'unique:ativos,ticker', 'regex:/[A-Z]{4}\d{1,2}/'],
            'empresa' => ['required', 'string', 'max:255'],
            'setor' => ['required', 'in:Bancos,Energia,Mineração,Petróleo,Varejo'],
            'preco' => ['required', 'numeric', 'min:0.01'],
            'estoque' => ['required', 'integer', 'min:0'],
        ];
    }
}
