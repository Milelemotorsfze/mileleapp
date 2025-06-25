<?php

namespace App\Http\Controllers;

use App\Models\VehicleInvoice;
use App\Models\So;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vehicles;
use App\Models\VehicleInvoiceItem;
use App\Models\Quotation;
use App\Models\ClientLeads;
use App\Models\MasterShippingPorts;
use App\Models\Clients;
use App\Models\QuotationDetail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class VehicleInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Log user activity
        (new UserActivityController)->createActivity('Open Vehicle Invoice Page');
        // Check if the request is an Ajax call
        if ($request->ajax()) {
            // Prepare the query to get the data
            $data = VehicleInvoice::select([
                    'vehicle_invoice.id',
                    'vehicle_invoice.invoice_number',
                    'vehicle_invoice.date',
                    'vehicle_invoice.discount',
                    'vehicle_invoice.sub_total',
                    'vehicle_invoice.net_amount',
                    'vehicle_invoice.vat',
                    'vehicle_invoice.shipping_charges',
                    'vehicle_invoice.gross_amount',
                    'clients.name',
                    'clients.phone',
                    'clients.email',
                    'pol.name as pol_name',
                    'pod.name as pod_name',
                ])
                ->leftJoin('so', 'vehicle_invoice.so_id', '=', 'so.id')
                ->leftJoin('clients', 'vehicle_invoice.clients_id', '=', 'clients.id')
                ->leftJoin('master_shipping_ports as pol', 'vehicle_invoice.pol', '=', 'pol.id')  // Aliased to avoid conflict
                ->leftJoin('master_shipping_ports as pod', 'vehicle_invoice.pod', '=', 'pod.id')
                ->groupBy('vehicle_invoice.id'); // Group by necessary fields
            // Return data in DataTables format
            return DataTables::of($data)->toJson();
        }
        // Return the view if not an Ajax call
        return view('vehicles.deliveryinvoice');
    }    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Create New Vehicle Invoice Page');
        $so = So::where(function ($query) {
            $query->where('status', '!=', 'Cancelled')
                  ->orWhereNull('status');
        })->get();
        return view('vehicles.deliveryinvoicecreate', compact('so'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'so' => 'required|exists:so,id',
            'discount' => 'nullable|numeric',
            'vat' => 'nullable|numeric',
            'shipping_charges' => 'nullable|numeric',
            'qty' => 'required|array',
            'unit_price' => 'required|array',
            'gross_amount' => 'required|array',
        ]);
        DB::beginTransaction();
        try {
            $vehicleInvoice = new VehicleInvoice();
            $so_id = $request->input('so');
            $so = So::find($so_id);
            $quotation = Quotation::find($so->quotation_id);
            $quotationdetails = QuotationDetail::where('quotation_id', $so->quotation_id)->first();
            $clientLeads = ClientLeads::where('calls_id', $quotation->calls_id)->first();
            $vehicleInvoice->date = $request->input('date');
            $vehicleInvoice->so_id = $so_id;
            $vehicleInvoice->clients_id = $clientLeads->clients_id;
            $vehicleInvoice->discount = $request->input('discount');
            $vehicleInvoice->vat = $request->input('vat');
            $vehicleInvoice->shipping_charges = $request->input('shipping_charges');
            $vehicleInvoice->pod = $quotationdetails->to_shipping_port_id;
            $vehicleInvoice->pol = $quotationdetails->shipping_port_id;
            $vehicleInvoice->invoice_number = $request->input('invoice_number');
            $grossAmountArray = $request->input('gross_amount');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $shipping_charges = $request->input('shipping_charges');
            $subTotal = array_sum($grossAmountArray);
            $vehicleInvoice->sub_total = $subTotal;
            $vehicleInvoice->net_amount = $subTotal- $discount;
            $vehicleInvoice->gross_amount = $subTotal + $vat + $shipping_charges;
            $vehicleInvoice->currency = $request->input('currency');
            $vehicleInvoice->save();
            $qtyArray = $request->input('qty');
            $unitPriceArray = $request->input('unit_price');
            $vehicleIds = $request->input('vehicle_id');
            foreach ($vehicleIds as $index => $vehicleId) {
                $vehicleInvoiceItem = new VehicleInvoiceItem();
                $vehicleInvoiceItem->vehicle_invoice_id = $vehicleInvoice->id;
                $vehicleInvoiceItem->vehicles_id = $vehicleId;
                $vehicleInvoiceItem->qty = $qtyArray[$index];
                $vehicleInvoiceItem->rate = $unitPriceArray[$index];
                $vehicleInvoiceItem->gross_amount = $grossAmountArray[$index];
                $vehicleInvoiceItem->save();
            }
            DB::commit();
            return redirect()->route('vehicleinvoice.index')->with('success', 'Vehicle invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating vehicle invoice: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(VehicleInvoice $vehicleInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VehicleInvoice $vehicleInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VehicleInvoice $vehicleInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleInvoice $vehicleInvoice)
    {
        //
    }
    public function getVehiclesBySO(Request $request)
    {
    $vehicles = DB::table('vehicles')
    ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
    ->join('brands', 'varaints.brands_id', '=', 'brands.id')
    ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->leftJoin('vehicle_invoice_items', 'vehicles.id', '=', 'vehicle_invoice_items.vehicles_id')
    ->whereNull('vehicle_invoice_items.vehicles_id')
    ->where('vehicles.so_id', $request->so_id)
    ->select(
        'vehicles.id',
        'varaints.my',               // Model Year
        'vehicles.vin',              // Vehicle Identification Number
        'vehicles.price',       // Unit Price
        'brands.brand_name',         // Brand Name
        'varaints.model_detail' // Model Line Name
    )
    ->get();
return response()->json($vehicles);
    }
    public function generateinvoicePDF(Request $request)
    {
    $vehicle_invoiceid = $request->vehicle_invoiceid;
    $invoice = VehicleInvoice::find($vehicle_invoiceid);
    $so = So::find($invoice->so_id)->first();
    $quotation = Quotation::find($so->quotation_id);
    $pol = optional(MasterShippingPorts::find($invoice->pol))->value('name') ?? '';
    $pod = optional(MasterShippingPorts::find($invoice->pod))->value('name') ?? '';      
    $quotationdetails = QuotationDetail::where('quotation_id', $so->quotation_id)->first();
    $client = Clients::find($invoice->clients_id);
// Modified query to join necessary tables and get the description data
$vehicleitems = DB::table('vehicle_invoice_items')
->join('vehicles', 'vehicle_invoice_items.vehicles_id', '=', 'vehicles.id')
->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
->join('brands', 'varaints.brands_id', '=', 'brands.id')
->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
->where('vehicle_invoice_items.vehicle_invoice_id', $vehicle_invoiceid)
->select(
    'vehicle_invoice_items.*',
    'varaints.my',               // Model Year
    'vehicles.vin',              // Vehicle Identification Number
    'brands.brand_name',  
    'vehicle_invoice_items.gross_amount as ga',        // Brand Name
    'varaints.model_detail'      // Model Line Name
)
->get();
    $data = [
        'clientName' => $client->name,
        'clientPhone' => $client->phone,
        'clientEmail' => $client->email,
        'invoiceNumber' => $invoice->invoice_number,
        'invoiceDate' => $invoice->date,
        'currency' => $invoice->currency,
        'sub_total' => $invoice->sub_total,
        'discount' => $invoice->discount,
        'net_amount' => $invoice->net_amount,
        'vat' => $invoice->vat,
        'shipping_charges' => $invoice->shipping_charges,
        'gross_amount' => $invoice->gross_amount,
        'pol' => $pol,
        'pod' => $pod,
        'vehicles' => $vehicleitems
    ];
    $pdf = Pdf::loadView('invoices.final', $data);
    return $pdf->stream('vehicle-invoice.pdf');
    }
}
