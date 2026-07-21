<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'breaking_news_ticker' => 'nullable|string',
            'hero_stat_cases_managed' => 'nullable|string|max:255',
            'stat_visas_approved' => 'nullable|string|max:255',
            'stat_complex_cases_coordinated' => 'nullable|string|max:255',
            'footer_address' => 'nullable|string',
            'footer_facebook_url' => 'nullable|string|max:500',
            'footer_youtube_url' => 'nullable|string|max:500',
            'footer_whatsapp_url' => 'nullable|string|max:500',
        ];
    }
}
