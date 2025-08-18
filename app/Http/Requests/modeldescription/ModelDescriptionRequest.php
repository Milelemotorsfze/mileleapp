<?php

namespace App\Http\Requests\modeldescription;

use Illuminate\Foundation\Http\FormRequest;

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
            'model_description' => 'required|string|unique:master_model_descriptions,model_description' . ($id ? ',' . $id : ''),
        ];
    }

    public function messages()
    {
        return [
            'model_description.unique' => 'Model detail is already existing !',
        ];
    }
} 