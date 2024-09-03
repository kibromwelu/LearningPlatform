<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIdentityRequest extends FormRequest
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
              // Identity Validation
              'identity.first_name' => 'required|string|max:255',
              'identity.middle_name' => 'string|max:255',
              'identity.last_name' => 'string|max:255',
              'identity.mother_name' => 'string|max:255',
              'identity.sex' => 'in:male,female,other',
              'identity.birth_date' => 'date',
              'identity.birth_place' => 'string|max:255',
              'identity.blood_type' => 'nullable|string|max:3',
              'identity.skin_color' => 'nullable|string|max:255',
              'identity.eye_color' => 'nullable|string|max:255',
              'identity.disability' => 'nullable|string|max:255',
  
              // Address Validation
              'address.residence_id' => 'integer',
              'address.mobile_number' => 'string|max:15',
              'address.phone' => 'nullable|string|max:15',
              'address.email' => 'email|max:255',
              'address.website' => 'nullable|string|max:255',
              'address.pobox' => 'nullable|string|max:255',
              'address.house_number' => 'string|max:255',
              'address.address_line_1' => 'string|max:255',
              'address.address_line_2' => 'nullable|string|max:255',
              'address.specific_location' => 'nullable|string|max:255',
              'address.tabia' => 'string|max:255',
              'address.city' => 'string|max:255',
              'address.country' => 'string|max:255',
  
              // Profile Validation
              'profile.biography' => 'nullable|string',
              'profile.category' => 'string|max:255',
              'profile.religion' => 'nullable|string|max:255',
              'profile.marital_status' => 'string|max:255',
              'profile.education_level' => 'string|max:255',
              'profile.mother_tongue_language' => 'string|max:255',
              'profile.income_source' => 'string|max:255',
              'profile.occupation' => 'string|max:255',
              'profile.employment_term' => 'string|max:255',
              'profile.organization' => 'nullable|string|max:255',
              'profile.household_size' => 'nullable|string|max:255',
              'profile.height' => 'nullable|numeric|max:255',
              'profile.weight' => 'nullable|numeric|max:255',
              'profile.avatar' => 'nullable|string|max:255',//0946909182 Nigisti
              'profile.cover' => 'nullable|string|max:255',
  
              // User Validation
              'user.password' => 'string|min:8',
        ];
    }
}
