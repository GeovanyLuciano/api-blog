<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
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

            "image" => ["required", "image", "mimes:png,jpg,jpeg,webp", "max:2048"],
            "name" => ["required", "string", "min:5", "max:20"],
            "description" => ["required", "string", "min:20", "max:255"],
            "categoria_id" => [
                "required",
                Rule::exists("categorias", "id")->where("user_id", Auth::id()),
            ],

        ];
    }
}
