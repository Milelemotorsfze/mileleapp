<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\WorkOrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class WorkOrderExportController extends Controller
{
    public function export()
    {
        return Excel::download(new WorkOrdersExport, 'work_orders.xlsx');
    }
}

