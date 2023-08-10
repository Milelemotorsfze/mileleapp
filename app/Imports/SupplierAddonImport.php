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
    // public $datr;
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
    // public $clean_text;
    public function collection(Collection $rows)
    {
    //     return 'k';
    //      Validator::make($rows->toArray(), [
    //          '*.addon_code' => 'required',
    //          '*.currency' => 'required',
    //          '*.purchase_price' => 'required',
    //      ])->validate();
    //     $dataError = [];
    //     for ($i=0; $i< count($rows); $i++) 
    //     {
    //         $currencyError = $priceErrror = $addonError = true;
    //         if(in_array(strtoupper($rows[$i]['currency']), ['AED','USD']))
    //         {
    //             $currencyError = true;
    //         }
    //         else
    //         {
    //             $currencyError = false;
    //             // dd('currency should be  AED or USD');
    //         }        
    //         if(is_numeric($rows[$i]['purchase_price']))
    //         {   
    //             $priceErrror = true;
    //         }
    //         else
    //         {
    //             $priceErrror = false;
    //             // dd('Purchase price should be a number');
    //         }
    //         $addonId = AddonDetails::where('addon_code',$rows[$i]['addon_code'])->select('id')->first();
    //         if($addonId)
    //         {
    //             $addonError = true;
    //         }
    //         else
    //         {
    //             $addonError = false;
    //             // dd('This addon code not exising in the system');
    //         }
    //         if($currencyError == false OR $priceErrror = false OR $addonError = false)
    //         {
    //             array_push($dataError, ["addon_code" => $rows[$i]['addon_code'], "addonError" => $addonError,"currency" => $rows[$i]['currency'], "currencyError" => $currencyError, "purchase_price" => $rows[$i]['purchase_price'], "priceErrror" => $priceErrror]); 
    //         }
           
    //         // if(fmod($row['purchase_price'], 1) !== 0.00){
    //         //     dd("It is decimal, so the decimal logic goes here.");
    //         // } else {
    //         //     dd("It is an Intereger, so the ineteger logic goes here.");
    //         // }

           
    //     // }
    //     // if(count($dataError) > 0)
    //     // {
    //     //     // $this->clean_text = $dataError;
    //     //     return $dataError;
    //     //     // $datr = $dataError;
    //     // }
    //     // // dd($dataError);
    // }
    foreach($rows as $row)
    {
        if($row['addon_code'] OR $row['currency'] OR $row['purchase_price'])
        {
            SupplierAddonTemp::create([
                'addon_code' => $row['addon_code'],
                'currency' => $row['currency'],
                'purchase_price' => $row['purchase_price'],
                'lead_time_min' => $row['lead_time_min'],
                'lead_time_max' => $row['lead_time_max'],
            ]);
        }  
    }
}
}