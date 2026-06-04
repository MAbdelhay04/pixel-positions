<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateEmployerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-employer-profile');
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'company_description' => ['nullable', 'string', 'max:3000'],
            'company_location' => ['nullable', 'string', 'max:120'],
            'company_website' => ['nullable', 'url', 'max:255'],
            'company_size' => ['nullable', 'string', 'max:80'],
            'industry' => ['nullable', 'string', 'max:120'],
            'logo' => ['nullable', File::image(true), 'max:2048'],
        ];
    }

    public function profileData(): array
    {
        return collect($this->safe()->except(['company_name', 'logo']))
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => filled($value))
            ->all();
    }
}
