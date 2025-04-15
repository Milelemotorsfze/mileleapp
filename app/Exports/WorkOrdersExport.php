<?php

namespace App\Exports;

use App\Models\WorkOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkOrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return WorkOrder::select(
            'work_orders.date',
            'work_orders.so_number',
            'work_orders.customer_name',
            'work_orders.freight_agent_name',
            'users.name as sales_person_name', // Fetch sales person name
            'work_orders.type',
            'work_orders.final_destination',
            'work_orders.port_of_loading',
            'work_orders.port_of_discharge',
            'work_orders.so_vehicle_quantity'
        )
        ->leftJoin('users', 'users.id', '=', 'work_orders.sales_person_id')
        ->get()
        ->makeHidden((new WorkOrder())->getAppends()); // Hide appended attributes
    }
    



public function headings(): array
{
    return [
        'Date',
        'SO Number',
        'Customer Name',
        'Freight Agent Name',
        'Sales Person Name', // Updated header
        'Type',
        'Final Destination',
        'Port of Loading',
        'Port of Discharge',
        'SO Vehicle Quantity'
    ];
}
}
