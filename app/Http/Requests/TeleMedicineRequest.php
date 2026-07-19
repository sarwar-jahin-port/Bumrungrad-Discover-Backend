<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeleMedicineRequest extends FormRequest
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
            'patientType' => 'required|in:new,returning',
            'preferredDoctor' => 'required|string|max:255',
            'timeSlot' => 'required|string|max:255',
            'specificConcern' => 'required|string',
            'contactDetails' => 'required|string|max:255',
        ];
    }
}
