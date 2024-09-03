<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIdentityRequest extends FormRequest
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
            'first_name' => 'string|max:255',
            'middle_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'mother_name' => 'string|max:255',
            'sex' => 'in:male,female,other',
            'birth_date' => 'date',
            'birth_place' => 'string|max:255',
            'blood_type' => 'nullable|string|max:3',
            'skin_color' => 'nullable|string|max:255',
            'eye_color' => 'nullable|string|max:255',
            'disability' => 'nullable|string|max:255',
        ];
    }
}
