<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
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
            "image" => ["sometimes", "required", "image", "mimes:png,jpg,jpeg,webp", "max:2048"],
            "name" => ["sometimes", "required", "string", "min:5", "max:20"],
            "description" => ["sometimes", "required", "string", "min:20", "max:255"],
            "categoria_id" => [
                "sometimes",
                "required",
                Rule::exists("categorias", "id")->where("user_id", Auth::id()),
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $fields = ["image", "name", "description", "categoria_id"];
            if (array_intersect($fields, array_keys($this->all())) === []) {
                $validator->errors()->add("post", "At least one post field must be provided.");
            }
        });
    }
}
