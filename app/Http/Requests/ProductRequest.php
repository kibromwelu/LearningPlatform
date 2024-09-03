<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'numeric|min:0',
            'user_id' => 'required|integer|min:0|max:9223372036854775807'
        ];

    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'description.required' => 'The description field is required.',
            // 'price.required' => 'The price field is required.',
        ];
    }
}