<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|file',
            'reference_url' => 'nullable|string',
        ];
    }
}
