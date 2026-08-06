<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
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
            'fullName' => 'required|string|max:255',
            'birthDate' => 'required|date',
            'whatsapp' => 'required|string|max:255',
            'patientType' => 'required|in:new,returning',
            'medicalConcern' => 'required|string',
            'passport' => 'required|file',
        ];
    }
}
