<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreaddressRequest extends FormRequest
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
            'address.identity_id' => 'required|string|max:255|unique:users',
            'address.email' => 'required|string|email|max:255|unique:users',
            'address.phone' => 'required|string|min:10',
            'residence_id'=>'string',
            'mobile_number'=>'required|string|min:10|unique:users',
            'website'=>'string',
            'pobox'=>'string',
            'house_number'=> 'string',
            'address_line_1'=>'string',
            'address_line_2'=> 'string',
            'specific_location'=>'string',
            'tabia'=>'string',
            'city'=>'string',
            'country'=>'string',
            'state'=>'string',
            'status'=>'string',
        ];
    }
}
