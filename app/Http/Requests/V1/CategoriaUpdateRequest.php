<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoriaUpdateRequest extends FormRequest
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
            "name" => ["sometimes", "required", "string", "min:3", "max:60"],
            "slug" => ["sometimes", "required", 'string', 'min:3', "max:20"],
            "description" => ["sometimes", "required", 'string', "min:10", 'max:255']
        ];
    }
}
