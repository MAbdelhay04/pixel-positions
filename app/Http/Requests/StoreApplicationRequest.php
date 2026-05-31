<?php

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Application::class)
            && $this->user()?->can('apply', $this->route('job'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resume'       => ['required', 'file', 'mimes:pdf,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function errorBag(): string
    {
        return 'applyJob';
    }
}
