<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearnerProgressRequest extends FormRequest
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
            'learner_id'=>'required|uuid',
            'course_id'=>'required|uuid',
            "topic_id"=>'uuid',
            "status"=>'string|in:in_progress,completed',
            "started_at"=>'date',
            "completed_at"=>'date'
        ];
    }
}
