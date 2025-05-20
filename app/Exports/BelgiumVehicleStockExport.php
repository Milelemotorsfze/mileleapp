<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class BelgiumVehicleStockExport implements FromCollection, WithHeadings
{
    /**
     * Export data as a collection.
     */
    public function collection()
    {
        return DB::table('vehicles')
            ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->join('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('master_models', 'master_models.variant_id', '=', 'varaints.id')
            ->join('color_codes as int_colours', 'vehicles.int_colour', '=', 'int_colours.id')
            ->join('color_codes as ext_colours', 'vehicles.ex_colour', '=', 'ext_colours.id')
            ->join('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->where('brands.brand_name', 'Toyota')
            ->where('varaints.is_dp_variant', 'Yes')
            ->where('vehicles.latest_location', '=', 38)
            ->whereNotIn('vehicles.latest_location', ['102', '153', '147'])
            ->where('purchasing_order.is_demand_planning_po', true)
            ->select(
                'master_models.model as Model',
                'master_models.sfx as SFX',
                'varaints.name as `Variant Name`',
                DB::raw('(SELECT COUNT(*) FROM vehicles 
                          WHERE varaints_id = varaints.id 
                          AND so_id IS NULL 
                          AND gdn_id IS NULL 
                          AND status = "Approved" 
                          AND (reservation_end_date IS NULL 
                               OR reservation_end_date < NOW())
                          ) as `Free Stock`'),
                DB::raw('(SELECT COUNT(*) FROM vehicles 
                          WHERE varaints_id = varaints.id 
                          AND status = "Approved" 
                          AND gdn_id IS NULL
                          ) as `Total Quantity`')
            )
            ->distinct()
            ->get();
    }

    /**
     * Define the headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Model',
            'SFX',
            'Variant Name',
            'Free Stock',
            'Total Quantity',
        ];
    }
}

