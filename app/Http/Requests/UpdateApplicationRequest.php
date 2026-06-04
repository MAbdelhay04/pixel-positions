<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('application'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $application = $this->route('application');

        return [
            'status' => [
                'required',
                'in:reviewing,interview,hired,rejected',
                function ($attribute, $value, $fail) use ($application) {
                    $next = ApplicationStatus::from($value);
                    if (! $application->status->canTransitionTo($next)) {
                        $fail(__('Invalid status transition.'));
                    }
                },
            ],
        ];
    }
}
