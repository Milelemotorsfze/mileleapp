<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportCSV extends Command
{
    protected $signature = 'export:csv';
    protected $description = 'Export CSV file with payment and stock aging';

    public function handle()
    {
        $data = DB::table('vehicles')
            ->select(
                'vehicles.estimation_date','vehicles.id', 'vehicles.vin', 'vehicles.price', 'vehicles.netsuit_grn_number', 'vehicles.netsuit_grn_date', 'vehicles.vin', 'vehicles.conversion',
                'purchasing_order.po_number', 'purchasing_order.po_date',
                DB::raw('COALESCE(so.so_number, "") as so_number'),
                DB::raw('COALESCE(so.so_date, "") as so_date'),
                DB::raw('COALESCE(movement_grns.grn_number, "") as grn_number'),
                DB::raw('COALESCE(movements_reference.date, "") as grn_date'),
                DB::raw('COALESCE(gdn.date, "") as gdn_date'),
                DB::raw('COALESCE(gdn.gdn_number, "") as gdn_number'),
                DB::raw('COALESCE(warehouse.name, "") as warehouse'),
                DB::raw('COALESCE(brands.brand_name, "") as brand'),
                DB::raw('COALESCE(master_model_lines.model_line, "") as model_line'),
                DB::raw('COALESCE(varaints.model_detail, "") as model_description'),
                DB::raw('COALESCE(varaints.name, "") as variant'),
                DB::raw('COALESCE(varaints.detail, "") as variant_detail'),
                DB::raw('COALESCE(users.name, "") as sales_person')
            )
            ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
            ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
            ->leftJoin('gdn', 'vehicles.gdn_id', '=', 'gdn.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
            ->leftJoin('users', 'so.sales_person_id', '=', 'users.id')
            ->get();

        $csvData = "PO Number,PO Date,Estimation Date,GRN,GRN Date,Netsuit GRN,Netsuit GRN Date,Payment Aging,Stock Aging,SO Number,SO Date,Sales Person,GDN,GDN Date,Warehouse,Brand,Conversion,Model Line,Model Description,Variant,Variant Details,VIN\n";
        foreach ($data as $vehicle) {
            // Your payment aging calculation code
            if ($vehicle->grn_date) {
                $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicle->id)->latest()->first();
                $savedDate = $paymentLog ? $paymentLog->date : null;
                $today = now()->format('Y-m-d');
                $numberOfDays = $savedDate ? \Carbon\Carbon::parse($savedDate)->diffInDays($today) : null;
            } else {
                $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicle->id)->latest()->first();
                $savedDate = $paymentLog ? $paymentLog->date : null;
                $today = \Carbon\Carbon::parse($vehicle->grn_date)->format('Y-m-d');
                $numberOfDays = $savedDate ? \Carbon\Carbon::parse($savedDate)->diffInDays($today) : null;
            }

            // Your stock aging calculation code
            if ($vehicle->grn_date && $vehicle->gdn_date === null) {
                $grn_date = \Carbon\Carbon::parse($vehicle->grn_date);
                $stockAging = $grn_date->diffInDays(\Carbon\Carbon::today());
            } elseif ($vehicle->gdn_date) {
                $aging = \Carbon\Carbon::parse($vehicle->grn_date)->diffInDays($vehicle->gdn_date);
            } else {
                $stockAging = null;
            }

            $csvData .= $vehicle->po_number ? "{$vehicle->po_number},{$vehicle->po_date}," : ",,";
            $csvData .= "{$vehicle->estimation_date},{$vehicle->grn_number},{$vehicle->grn_date},{$vehicle->netsuit_grn_number},{$vehicle->netsuit_grn_date},{$numberOfDays},{$stockAging},";
            $csvData .= "{$vehicle->so_number},{$vehicle->so_date},{$vehicle->sales_person},";
            $csvData .= "{$vehicle->gdn_number},{$vehicle->gdn_date},";
            $csvData .= "{$vehicle->warehouse},{$vehicle->brand},{$vehicle->conversion},{$vehicle->model_line},{$vehicle->model_description},";
            $csvData .= "{$vehicle->variant},{$vehicle->variant_detail},{$vehicle->vin}\n";
        }

        $fileName = date('Y-m') . '-vehicles.csv';
        $filePath = public_path('exports/' . $fileName);

        // Save CSV file
        file_put_contents($filePath, $csvData);

        $this->info('CSV file exported successfully to: ' . $filePath);
    }
}
