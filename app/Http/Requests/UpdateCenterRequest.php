<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCenterRequest extends FormRequest
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
            'cover_photo' => 'nullable|file|image',
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'informations' => 'nullable|string',
            'conditions' => 'nullable|string',
            'treatments' => 'nullable|string',
            'floor_map' => 'nullable|file|image',
            'operational_hours' => 'nullable|string',
            'whatsapp_hotline' => 'nullable|string|max:50',
        ];
    }
}
