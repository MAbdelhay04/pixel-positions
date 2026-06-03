<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileSkillsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skills'   => ['nullable', 'array', 'max:15'],
            'skills.*' => ['string', 'min:2', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'skills.max' => 'You can only add up to 15 skills.',
            'skills.*.min' => 'Each skill must be at least 2 characters.',
            'skills.*.max' => 'Each skill must not exceed 30 characters.',
        ];
    }
}
