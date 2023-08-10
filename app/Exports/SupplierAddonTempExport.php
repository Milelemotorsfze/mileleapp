<?php

namespace App\Exports;

use App\Models\SupplierAddonTemp;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierAddonTempExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'addon_code',
            'currency',
            'purchase_price',
            'lead_time_min',
            'lead_time_max'
        ];
    } 
    public function collection()
    {
        return SupplierAddonTemp::all();
    }
}
