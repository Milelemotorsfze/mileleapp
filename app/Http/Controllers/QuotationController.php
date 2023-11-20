<?php

namespace App\Http\Controllers;

use App\Models\AddonDetails;
use App\Models\OtherLogisticsCharges;
use App\Models\quotation;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\QuotationClient;
use App\Models\QuotationDetail;
use App\Models\QuotationItem;
use App\Models\Shipping;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use App\Models\Varaint;
use App\Models\Vehicles;
use App\Models\Vehiclescarts;
use App\Models\MasterModelLines;
use App\Models\CartAddon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Monarobase\CountryList\CountryListFacade;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $latestQuotation = quotation::where('created_by', auth()->user()->id)
                    ->latest()
                    ->first();
    $callsId = $latestQuotation->calls_id;
    $data = Calls::select('name', 'email')
            ->where('id', $callsId)
            ->first();
    $countries = CountryListFacade::getList('en');
    $vehicles_id = Vehiclescarts::where('created_by', auth()->user()->id)->pluck('vehicle_id');
    $items = Vehicles::whereIn('id', $vehicles_id)->get();
    return view('quotation.add_new', compact('data', 'countries', 'items', 'vehicles_id'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return dd($request->all());
        DB::beginTransaction();

        $call = Calls::find($request->calls_id);

        $call->company_name = $request->company_name;
        $call->name = $request->name;
        $call->phone = $request->phone;
        $call->email = $request->email;
        $call->address = $request->address;
        $call->save();

        $quotation = new Quotation();
        $quotation->deal_value = $request->deal_value;
        $quotation->sales_notes = $request->remarks;
        $quotation->created_by = Auth::id();
        $quotation->calls_id = $request->calls_id;
        $quotation->currency = $request->currency;
        $quotation->document_type = $request->document_type;
        if($request->document_type == 'Proforma') {
            $quotation->document_type = 'Proforma Invoice';
        }
        $quotation->shipping_method = $request->shipping_method;
        $quotation->remarks = $request->remarks;
        $quotation->save();

        $quotationDetail = new QuotationDetail();
        $quotationDetail->quotation_id  = $quotation->id;
        $quotationDetail->final_destination  = $request->final_destination;
        $quotationDetail->incoterm  = $request->incoterm;
        $quotationDetail->place_of_delivery  = $request->place_of_delivery;
        $quotationDetail->place_of_supply  = $request->place_of_supply;
        $quotationDetail->document_validity  = $request->document_validity;
        $quotationDetail->system_code  = $request->system_code;
        $quotationDetail->payment_terms  = $request->payment_terms;
        $quotationDetail->representative_name = $request->representative_name;
        $quotationDetail->representative_number = $request->representative_number;
        $quotationDetail->cb_name = $request->cb_name;
        $quotationDetail->cb_number = $request->cb_number;
        $quotationDetail->advance_amount = $request->advance_amount;
        $quotationDetail->save();

        foreach ($request->prices as $key => $price) {
           $quotationItem = new QuotationItem();
           $quotationItem->unit_price = $price;
           $quotationItem->quantity = $request->quantities[$key];
           $quotationItem->description = $request->descriptions[$key];
           $quotationItem->total_amount = $request->total_amounts[$key];
           $quotationItem->quotation_id = $quotation->id;
           $quotationItem->created_by = Auth::id();

           if($request->types[$key] == 'Shipping') {

               $item = Shipping::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Certification') {

               $item = ShippingCertification::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Shipping-Document') {

               $item = ShippingDocuments::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Vehicle') {
               $item = Vehicles::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Other') {

               $item = OtherLogisticsCharges::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'ModelLine') {
               $item = MasterModelLines::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Accessory' || $request->types[$key] == 'SparePart' || $request->types[$key] == 'Kit') {

               $item = AddonDetails::find($request->reference_ids[$key]);

           }
            $quotationItem->reference()->associate($item);
            $quotationItem->save();
        }
        DB::commit();
//        $quotationItem = QuotationItem::where('quotation_id', $quotation->id)->first();
//        $quotation = Quotation::find(58);
//        $call = Calls::find($quotation->calls_id);
//        $quotationDetail = QuotationDetail::where('quotation_id', 58)->first();

        $vehicles =  QuotationItem::where("reference_type", 'App\Models\Vehicles')
            ->where('quotation_id', $quotation->id)->get();

        $variants = QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)->get();

        $addons = QuotationItem::where('reference_type','App\Models\AddonDetails')
            ->where('quotation_id', $quotation->id)->get();
        $shippingCharges = QuotationItem::where('reference_type','App\Models\Shipping')
            ->where('quotation_id', $quotation->id)->get();
        $shippingDocuments = QuotationItem::where('reference_type','App\Models\ShippingDocuments')
            ->where('quotation_id', $quotation->id)->get();
        $otherDocuments = QuotationItem::where('reference_type','App\Models\OtherLogisticsCharges')
            ->where('quotation_id', $quotation->id)->get();
        $shippingCertifications = QuotationItem::where('reference_type','App\Models\ShippingCertification')
            ->where('quotation_id', $quotation->id)->get();
//        return view('proforma.proforma_invoice', compact('quotationItem','quotation','call','quotationDetail',
//            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications'));
        $data = [];
        $data['sales_person'] = Auth::user()->name;
        $data['client_email'] = $call->email;
        $data['client_name'] = $call->name;
        $data['customer_reference_number'] = $call->id;
        $data['client_phone'] = $call->phone;
        $data['client_address'] = $call->address;

        $pdfFile = Pdf::loadView('proforma.proforma_invoice', compact('quotation','data','quotationDetail',
            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications','variants'));

        $filename = 'quotation_'.$quotation->id.'.pdf';

        $directory = public_path('Quotations');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdfFile->save($directory . '/' . $filename);

        $pdf = $this->pdfMerge($quotation->id);
        $file = 'Quotation_'.$quotation->id.'_'.date('Y_m_d').'.pdf';

        $quotation->file_path = $file;
        $quotation->save();
        if (file_exists(public_path('Quotations/' . $filename))) {
            unlink(public_path('Quotations/' . $filename));
        }
        return $pdf->Output($file);

//        return $pdfFile->stream("Halloa.pdf");

    }
    public function pdfMerge($quotationId)
    {
        $quotation = Quotation::find($quotationId);
        $filename = 'quotation_'.$quotationId.'.pdf';

        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        $pdf->setPrintHeader(false);
        $files[] = 'Quotations/'.$filename;

        $files[] = 'Quotations/quotation_attachment_documents.pdf';

        foreach ($files as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($i=0; $i < $pageCount; $i++)
            {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($i+1);
                $pdf->useTemplate($tplIdx);
            }
        }
        return $pdf;
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Calls::where('id',$id)->first();
        $quotation = quotation::updateOrCreate([
            'calls_id' => $data->id,
            'created_by' => auth()->user()->id
        ]);
     //   echo $quotation;
         $vehicles = Vehicles::query()
                     ->select('*')
                     ->addSelect('vehicles.id as veh_id')
                     ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                     ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                     ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                     ->where('vehicles.status', '=', 'New')
                     ->get();
         $variants = Varaint::get();
         $brand = Brand::get();
         $countries = CountryListFacade::getList('en');
        // return view('quotation.add_new',compact('data', 'countries', 'variants', 'brand'));
        return view('quotation.sreach',compact('data', 'countries', 'variants', 'brand', 'vehicles', 'quotation'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(){

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, quotation $quotation)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(quotation $quotation)
    {
        //
    }
    public function getmy(Request $request)
    {
        $data = Varaint::where('brands_id', $request->brand)
        ->groupBy('my')
        ->pluck('my')
        ->toArray();
    return $data;
    }
    public function getmodelline(Request $request)
    {
        $masterModelLineids = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->groupBy('master_model_lines_id')
        ->pluck('master_model_lines_id')
        ->toArray();
       $data = MasterModelLines::whereIn('id', $masterModelLineids)
       ->pluck('model_line')
       ->toArray();
       return $data;
    }
    public function getsubmodel(Request $request)
    {
        $modellinearray = MasterModelLines::where('model_line', $request->model_line)
        ->pluck('id')
        ->toArray();
        $data = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->whereIn('master_model_lines_id', $modellinearray)
        ->groupBy('sub_model')
        ->pluck('sub_model')
        ->toArray();
        return $data;
    }
    public function gettrim(Request $request)
    {
        $modellinearray = MasterModelLines::where('model_line', $request->model_line)
        ->pluck('id')
        ->toArray();
        $data = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->whereIn('master_model_lines_id', $modellinearray)
        ->groupBy('sub_model')
        ->pluck('sub_model')
        ->toArray();
        return $data;
    }
    public function addvehicles(Request $request)
    {
        if($request->actiond == "addvehicles"){
        $data = Vehiclescarts::updateOrCreate([
                'vehicle_id' => $request->vehicles_id,
                'quotation_id' => $request->quotation_id,
                'created_by' => auth()->user()->id
            ]);
        return $data;
        }
    }
    public function removeVehicle($id)
{
    $data = Vehiclescarts::where('vehicle_id', $id)->delete();
    return redirect()->back();
}
public function addqaddone(Request $request)
    {
        if($request->anu == "addadones"){
         $data = CartAddon::updateOrCreate([
                 'cart_id' => $request->cart_id,
                 'addon_id' => $request->addon_id,
                 'created_by' => auth()->user()->id
             ]);
             $addon_id = $request->addon_id;
             $results = DB::table('addon_details')
             ->select('addon_details.addon_code', 'addons.name', 'addon_details.lead_time', 'addon_details.selling_price')
             ->join('addon_types', 'addon_details.id', '=', 'addon_types.addon_details_id')
             ->join('addons', 'addon_details.addon_id', '=', 'addons.id')
             ->where('addon_details.id', '=', $addonid)
             ->get();
         return $results;
         }
    }
}
