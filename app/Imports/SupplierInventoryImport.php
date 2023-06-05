<?php

namespace App\Imports;

use App\Models\SupplierInventory;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierInventoryImport implements WithValidation,WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        return new SupplierInventory([
            'steering'     => $row['steering'],
            'model'        => $row['model'],
        ]);
    }

    public function rules(): array
    {
        return [
            'steering' => 'required',
            'model' => 'required',
            // Add more validation rules as needed
        ];
    }

    public function customValidationMessages()
    {
        return [
            'steering.required' => 'Steering is required.',
            'model.required' => 'Model is required.',
            // Add custom error messages for specific validation rules
        ];
    }

}
