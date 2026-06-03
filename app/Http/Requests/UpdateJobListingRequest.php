<?php

namespace App\Http\Requests;

use App\Enums\JobLocation;
use App\Enums\JobStatus;
use App\Enums\JobType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateJobListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('job'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url'],
            'salary_range' => ['required', 'string', 'max:25'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
            'description'   => ['nullable', 'max:500'],
            'location'  => ['required', new Enum(JobLocation::class)],
            'type'  => ['required', new Enum(JobType::class)],
            'status'  => ['required', new Enum(JobStatus::class)],
            'skills' => ['nullable', 'array', 'max:10'],
            'skills.*' => ['string', 'min:2', 'max:30'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'min:2', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'skills.*.min' => 'Each skill must be at least 2 characters.',
            'skills.*.max' => 'Each skill must not exceed 30 characters.',
            'skills.*.string' => 'Each skill must be a valid string.',

            'tags.*.min' => 'Each tag must be at least 2 characters.',
            'tags.*.max' => 'Each tag must not exceed 30 characters.',
            'tags.*.string' => 'Each tag must be a valid string.',
        ];
    }

    public function validatedJobData()
    {
        return [
            'title' => $this->validated('title'),
            'url' => $this->validated('url'),
            'salary_range' => $this->validated('salary_range'),
            'category_id' => $this->validated('category_id'),
            'description' => $this->validated('description'),
            'location' => $this->validated('location'),
            'type' => $this->validated('type'),
            'status' => $this->validated('status'),
        ];
    }
}
