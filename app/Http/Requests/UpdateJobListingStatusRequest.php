<?php

namespace App\Http\Requests;

use App\Enums\JobStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateJobListingStatusRequest extends FormRequest
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
        $job = $this->route('job');

        return [
            'status' => [
                'required',
                new Enum(JobStatus::class),
                function ($attribute, $value, $fail) use ($job) {
                    $next = JobStatus::tryFrom($value);
                    if (! $next) {
                        $fail(__('Invalid status.'));
                    } elseif (! $job->status->canTransitionTo($next)) {
                        $fail(__('Invalid status transition.'));
                    }
                },
            ],
        ];
    }
}
