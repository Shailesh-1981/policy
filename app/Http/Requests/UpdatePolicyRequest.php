<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdatePolicyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'policy_number'    => 'required|string|max:50', // Removed unique validation
            'policy_type'      => 'required|string|max:255',
            'premium_amount'   => 'required|numeric|min:0',
            'coverage_details' => 'required|string',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date',
            'status'           => 'required|in:active,expired,canceled',
        ];
    }

    public function messages(): array
    {
        return [
            'policy_number.required' => 'Policy number is required.',
            'policy_type.required'   => 'Please select a policy type.',
            'premium_amount.required' => 'Premium amount is required.',
            'premium_amount.numeric'  => 'Premium amount must be a valid number.',
            'start_date.required'    => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.required'      => 'End date is required.',
            'end_date.after'         => 'End date must be after the start date.',
            'status.required'        => 'Please select a policy status.',
            'status.in'              => 'Status must be one of: active, expired, or canceled.',
        ];
    }
}
