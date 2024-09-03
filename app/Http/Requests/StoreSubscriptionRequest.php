<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSubscriptionRequest extends FormRequest
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
            // 'identity_id' => 'required|uuid|max:255',
            // 'id' => 'required|uuid',
            'identity_id' => 'required|numeric|max:255',
            'package' => 'string|in:basic,standard,premium,family,enterprise,freemium',
            'mode' => 'string|in:monthly,quarterly,annually',
            'payment' => 'numeric',
            'currency' => 'string|in:ETB,USD'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }

    public function messages()
    {
        return [
            'identity_id.required' => 'Identity ID is required.',
            'identity_id.unique' => 'There has been a subscription added to this client',
        ];
    }
}
