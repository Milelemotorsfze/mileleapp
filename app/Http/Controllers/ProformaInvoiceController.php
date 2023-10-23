<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\Brand;

class ProformaInvoiceController extends Controller
{
    public function proforma_invoice($callId) {
        $brands = Brand::all();
        $callDetails = Calls::where('id', $callId)->first();
        return view('proforma.invoice', compact('callDetails', 'brands'));
    }
}
