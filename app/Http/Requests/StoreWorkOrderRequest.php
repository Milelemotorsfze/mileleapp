<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\So;

class StoreWorkOrderRequest extends FormRequest
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
            'type' => 'required|string',
            'so_number' => [
                'required',
                'string',
                'regex:/^SO-\d{6}$/',
                'not_in:SO-000000',
                function ($attribute, $value, $fail) {
                    // Check if SO exists in sales order table and is not cancelled
                    $so = So::where('so_number', $value)
                        ->where(function ($query) {
                            $query->where('status', '!=', 'Cancelled')
                                ->orWhereNull('status');
                        })
                        ->first();
                    
                    if (!$so) {
                        $fail('The selected sales order does not exist or has been cancelled.');
                    }
                }
            ],
            'contact_number' => 'regex:/^[0-9]$/',
            'customer_email' => 'nullable|email|max:255',
            'customer_representative_email' => 'nullable|email|max:255',
            'freight_agent_email' => 'nullable|email|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'so_number.required' => 'Sales Order number is required.',
            'so_number.regex' => 'Sales Order number must be in format SO-######.',
            'so_number.not_in' => 'SO-000000 is not a valid Sales Order number.',
        ];
    }
}
