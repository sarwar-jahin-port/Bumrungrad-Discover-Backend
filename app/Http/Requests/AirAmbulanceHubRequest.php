<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AirAmbulanceHubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => 'required|string|max:255',
            'office_name' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'floor_map' => 'nullable|string',
            'address' => 'nullable|string',
            'phone1' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'whatsapp_hotline' => 'nullable|string|max:255',
            'operational_hours' => 'nullable|string',
            'map_embed_url' => 'nullable|string',
        ];
    }
}
