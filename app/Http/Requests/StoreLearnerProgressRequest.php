<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLearnerProgressRequest extends FormRequest
{
    
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
            'learner_id'=>'uuid',
            'course_id'=>'required|uuid',
            "topic_id"=>'uuid',
            "status"=>'string|in:in_progress,completed',
            "started_at"=>'date',
            "completed_at"=>'date'
        ];
    }
}
