<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'date' => 'required|date',
            'so_number' => 'required|string|regex:/^SO-\d{6}$/|not_in:SO-000000',
            // 'batch' =>'required',
            // 'wo_number' =>'required',
            'contact_number' => 'regex:/^[0-9]$/',
            'customer_email' => 'nullable|email|max:255',
            'customer_representative_email' => 'nullable|email|max:255',
            'freight_agent_email' => 'nullable|email|max:255',
            // Add other fields and validation rules as needed
            // 'customer_company_number.main' => 'required|numeric',
        ];
    }
}
