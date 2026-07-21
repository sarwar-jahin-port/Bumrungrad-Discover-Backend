<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'blogTitle' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'blogImage' => 'nullable|file',
            'blogDescription' => 'required|string',
        ];
    }
}
