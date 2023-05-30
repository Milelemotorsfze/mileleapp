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
            'purchase_price'
        ];
    } 
    public function collection()
    {
        return SupplierAddonTemp::all();
    }
}
