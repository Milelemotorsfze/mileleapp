<?php

namespace App\Imports;

use App\Models\SupplierAddonTemp;
use App\Models\AddonDetails;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class SupplierAddonImport implements ToCollection, WithHeadingRow
{
    // ToModel,
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     return new SupplierAddonTemp([
    //         'addon_code'    => $row['addon_code'], 
    //         'currency' => $row['currency'],
    //         'purchase_price' => $row['purchase_price'],
    //     ]);
    // }
    public function collection(Collection $rows)
    {
//          Validator::make($rows->toArray(), [
//              '*.addon_code' => 'required',
//              '*.currency' => 'required',
//              '*.purchase_price' => 'required',
//          ])->validate();
//   dd('ji');
        foreach ($rows as $row) {
            if(in_array($row['currency'], ['AED','USD']))
            {

            }
            else
            {
                dd('currency should be  AED or USD');
            }        
            if(is_numeric($row['purchase_price']))
            {   

            }
            else
            {
                dd('Purchase price should be a number');
            }
            $addonId = AddonDetails::where('addon_code',$row['addon_code'])->select('id')->first();
            if($addonId)
            {

            }
            else{
                dd('This addon code not exising in the system');
            }
            // if(fmod($row['purchase_price'], 1) !== 0.00){
            //     dd("It is decimal, so the decimal logic goes here.");
            // } else {
            //     dd("It is an Intereger, so the ineteger logic goes here.");
            // }

            SupplierAddonTemp::create([
                'addon_code' => $row['addon_code'],
                'currency' => $row['currency'],
                'purchase_price' => $row['purchase_price'],
            ]);
        }
    }
}