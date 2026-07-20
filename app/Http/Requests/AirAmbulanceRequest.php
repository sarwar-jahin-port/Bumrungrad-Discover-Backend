<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AirAmbulanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entry_date' => 'required|string|max:255',
            'passport_copy' => 'required|file',
            'summary' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
