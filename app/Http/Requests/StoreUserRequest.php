<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/',
            'cpf'  => 'required|cpf',
        ];
    }
}
