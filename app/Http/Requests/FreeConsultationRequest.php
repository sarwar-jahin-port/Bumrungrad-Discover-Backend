<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreeConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:255',
            'medical_concern' => 'required|string',
        ];
    }
}
