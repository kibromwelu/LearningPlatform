<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
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
            'date' => 'date',
            'language' => 'string',
            'refNumber' => 'string',
            'address' => 'string',
            'to' => 'string|required',
            'subject' => 'string|required',
            'message' => 'string|required',
            'carbon_copy_to' => 'array|required'
        ];
    }
}
