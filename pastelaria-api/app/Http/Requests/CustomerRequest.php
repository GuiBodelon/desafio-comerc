<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $this->route('customer'),
            'phone' => 'required|string',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'zip_code' => 'required|string',
        ];
    }
}
