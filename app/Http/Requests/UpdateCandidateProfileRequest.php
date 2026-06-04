<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update-candidate-profile');
    }

    public function rules(): array
    {
        return [
            'headline' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'work_experience' => ['nullable', 'string', 'max:4000'],
            'education' => ['nullable', 'string', 'max:3000'],
            'location' => ['nullable', 'string', 'max:120'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'availability' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function profileData(): array
    {
        return collect($this->validated())
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => filled($value))
            ->all();
    }
}
