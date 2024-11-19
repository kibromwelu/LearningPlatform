<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLetterRequest extends FormRequest
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
            'date' => 'date|nullable',
            'language' => 'string|nullable',
            'refNumber' => 'string|nullable',
            'to' => 'string|nullable',
            'subject' => 'string|nullable',
            'message' => 'string|nullable',
            'carbon_copy_to' => 'array|nullable'
        ];
    }
}
