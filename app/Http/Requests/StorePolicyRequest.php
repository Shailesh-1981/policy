<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
            'policy_number'    => 'required|string|max:50|unique:policy,policy_number',
            'policy_type'             => 'required|string|max:255',
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
            'policy_number.unique'   => 'This policy number already exists.',
            'policy_type.required'          => 'Please provide the policy type.',
            'premium_amount.required' => 'Premium amount is required.',
            'premium_amount.numeric'  => 'Premium amount must be a valid number.',
            'start_date.required'    => 'Start date is required.',
            'end_date.required'      => 'End date is required.',
            'end_date.after'         => 'End date must be after the start date.',
            'status.in'              => 'Status must be active, expired, or canceled.',
        ];
    }
}
