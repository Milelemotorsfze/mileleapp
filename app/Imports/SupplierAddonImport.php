<?php

namespace App\Imports;

use App\Models\SupplierAddonTemp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierAddonImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SupplierAddonTemp([
            'addon_code'    => $row['addon_code'], 
            'currency' => $row['currency'],
            'purchase_price' => $row['purchase_price'],
        ]);
    }
}