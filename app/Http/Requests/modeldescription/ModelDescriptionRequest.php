<?php

namespace App\Http\Requests\modeldescription;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MasterModelDescription;

class ModelDescriptionRequest extends FormRequest
{
    public function authorize()
    {
        // Adjust as needed for your auth logic
        return auth()->check();
    }

    public function rules()
    {
        $id = $this->route('modeldescription') ?? $this->route('id');
        return [
            'steering' => 'required|string',
            'brands_id' => 'required|exists:brands,id',
            'master_model_lines_id' => 'required|exists:master_model_lines,id',
            'grade' => 'nullable|string',
            'fuel_type' => 'required|string',
            'gearbox' => 'nullable|string',
            'drive_train' => 'nullable|string',
            'window_type' => 'nullable|string',
            'model_description' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($id) {
                    // Normalize the value: trim whitespace and convert to uppercase
                    $normalizedValue = strtoupper(trim($value));
                    
                    $query = MasterModelDescription::whereRaw('UPPER(TRIM(model_description)) = ?', [$normalizedValue]);
                    
                    // Exclude current record when updating
                    if ($id) {
                        $query->where('id', '!=', $id);
                    }
                    
                    if ($query->exists()) {
                        $fail('Model detail is already existing !');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'model_description.unique' => 'Model detail is already existing !',
        ];
    }
} 