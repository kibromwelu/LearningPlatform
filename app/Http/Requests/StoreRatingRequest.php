<?php

namespace App\Http\Requests;

use App\Models\Rating;
use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
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
                    
            'rating' => 'integer|max:5|min:0',//value
            'rated_id' => 'uuid',//id
            'is_helpful'=>'boolean',
            'remark' => 'string',
            "rated_type" => 'required|string|in:user,course',// either 'course' or 'user'
        ];
    }
}
