<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticker' => 'required|regex:/^[A-Z]{4}[0-9]{1,2}$/|unique:ativos,ticker',
            'empresa' => 'required|string|max:255',
            'setor' => 'required|in:Bancos,Energia,Mineração,Petróleo,Varejo',
            'preco' => 'required|numeric|min:0.01',
            'estoque' => 'required|integer|min:0',
        ];
    }
}
