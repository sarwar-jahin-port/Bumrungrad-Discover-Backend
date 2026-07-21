<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientStoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'story' => 'required|string',
        ];
    }
}
