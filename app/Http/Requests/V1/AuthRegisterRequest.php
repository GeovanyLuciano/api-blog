<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    // protected $stopOnFirstFailure = true;
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
            'name' => 'required|string|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome obrigatorio.',
            'name.string' => 'Nome deve ser um texto.',
            'name.min' => 'Nome deve ter pelo menos 4 caracteres.',
            'email.required' => 'Email obrigatorio.',
            'email.email' => 'Email invalido.',
            'email.unique' => 'Email ja esta em uso.',
            'password.required' => 'Senha obrigatoria.',
            'password.string' => 'Senha deve ser um texto.',
            'password.min' => 'Senha deve ter pelo menos 8 caracteres.',
        ];
    }
}
