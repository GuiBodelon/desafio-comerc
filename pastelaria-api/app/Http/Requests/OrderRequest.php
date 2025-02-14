<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id', // Verifica se o customer_id existe
            'products' => 'required|array',  // Verifica se o campo 'products' é um array
            'products.*.product_id' => 'required|exists:products,id', // Verifica se cada 'product_id' existe
            'products.*.quantity' => 'required|integer|min:1',  // Verifica se cada 'quantity' é um inteiro maior que 0
        ];
    }
}
