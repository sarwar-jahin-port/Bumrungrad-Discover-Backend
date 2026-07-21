<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'blogImage' => 'required|file',
            'blogDescription' => 'required|string',
        ];
    }
}
