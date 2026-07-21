<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'newsTitle' => 'required|string|max:255',
            'newsImage' => 'required|file',
            'newsDescription' => 'required|string',
            'newsSlogan' => 'nullable|string',
        ];
    }
}
