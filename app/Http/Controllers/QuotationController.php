<?php

namespace App\Http\Controllers;

use App\Models\AddonDetails;
use App\Models\AgentCommission;
use App\Models\Country;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\MasterShippingPort;
use App\Models\OtherLogisticsCharges;
use App\Models\Quotation;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\QuotationClient;
use App\Models\QuotationDetail;
use App\Models\QuotationItem;
use App\Models\QuotationSubItem;
use App\Models\Setting;
use App\Models\Shipping;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use App\Models\Varaint;
use App\Models\Vehicles;
use App\Models\Vehiclescarts;
use App\Models\MasterModelLines;
use App\Models\CartAddon;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
    $latestQuotation = Quotation::where('created_by', auth()->user()->id)
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
//        dd($request->all());
//        $request->validate([
//            'prices' => 'required'
//        ]);

        DB::beginTransaction();
        $isVehicle = 0;
        $call = Calls::find($request->calls_id);
        $call->status = 'Quoted';
        $call->save();

        $call->company_name = $request->company_name;
        $call->name = $request->name;
        $call->phone = $request->phone;
        $call->email = $request->email;
        $call->address = $request->address;
        $call->save();

        $quotation = new Quotation();
        if($request->currency == 'AED') {
            $quotation->deal_value = $request->total;
        }else{
            $quotation->deal_value = $request->deal_value;

        }
        $quotation->sales_notes = $request->remarks;
        $quotation->created_by = Auth::id();
        $quotation->calls_id = $request->calls_id;
        $quotation->currency = $request->currency;
        $quotation->document_type = $request->document_type;
        $quotation->date = Carbon::now();
        if($request->document_type == 'Proforma') {
            $quotation->document_type = 'Proforma Invoice';
        }
        $quotation->shipping_method = $request->shipping_method;
        $quotation->save();

        $quotationDetail = new QuotationDetail();
        $quotationDetail->quotation_id  = $quotation->id;
        $quotationDetail->country_id  = $request->country_id;
        $quotationDetail->incoterm  = $request->incoterm;
        $quotationDetail->shipping_port_id   = $request->shipping_port_id ;
        $quotationDetail->place_of_supply  = $request->place_of_supply;
        $quotationDetail->document_validity  = $request->document_validity;
        $quotationDetail->system_code  = $request->system_code;
        $quotationDetail->payment_terms  = $request->payment_terms;
        $quotationDetail->representative_name = $request->representative_name;
        $quotationDetail->representative_number = $request->representative_number;
        $quotationDetail->cb_name = $request->selected_cb_name;
        $quotationDetail->cb_number = $request->cb_number;
        $quotationDetail->agents_id = $request->agents_id;
        $quotationDetail->advance_amount = $request->advance_amount;
        $quotationDetail->save();

        if($request->agents_id) {
            $agentCommission = new AgentCommission();
            $agentCommission->commission = $request->system_code ?? '';
            $agentCommission->status = 'Quotation';
            $agentCommission->agents_id  =  $request->agents_id ?? '';
            $agentCommission->quotation_id  = $quotation->id;
            $agentCommission->created_by = Auth::id();
            $agentCommission->save();
        }

//        $quotationItemIds = [];
//        $quotationSubItemKeys = [];
        foreach ($request->prices as $key => $price) {
           $quotationItem = new QuotationItem();
           $quotationItem->unit_price = $price;
           $quotationItem->quantity = $request->quantities[$key];
           $quotationItem->description = $request->descriptions[$key];
           $quotationItem->total_amount = $request->total_amounts[$key];
           $quotationItem->quotation_id = $quotation->id;
           $quotationItem->is_addon = $request->is_addon[$key];
           $quotationItem->is_enable = isset($request->is_hide[$key]) ? true : false;
           $quotationItem->created_by = Auth::id();

           if($request->types[$key] == 'Shipping') {

               $item = Shipping::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Certification') {

               $item = ShippingCertification::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Shipping-Document') {

               $item = ShippingDocuments::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'Vehicle') {
               if($request->reference_ids[$key] != 'Other') {
                   $item = Varaint::find($request->reference_ids[$key]);
               }
                //confirming it is a vehicle
//                    $isVehicle = 1;
//                    $variant = Varaint::find($request->reference_ids[$key]);
//                    if($variant) {
//                        $vehicleModelLineId = $variant->master_model_lines->id ?? '';
//                    }
           }else if($request->types[$key] == 'Other') {
               $item = OtherLogisticsCharges::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'ModelLine') {
               $item = MasterModelLines::find($request->reference_ids[$key]);
//               //confirming it is a vehicle
//               if($request->is_addon[$key] == 0) {
//                   $isVehicle = 1;
//                   $vehicleModelLineId = $request->reference_ids[$key];
//                   info("vehicle - > model line");
//                   info($key);
//               }

           }else if($request->types[$key] == 'Accessory' || $request->types[$key] == 'SparePart' || $request->types[$key] == 'Kit') {
               if($request->reference_ids[$key] != 'Other') {
                   $item = AddonDetails::find($request->reference_ids[$key]);
               }

           }
            $quotationItem->reference()->associate($item);
            $quotationItem->save();

//            if($isVehicle == 1){
//                $arrayKeys = array_keys($request->model_lines, $vehicleModelLineId);
//                if (count($arrayKeys) > 0) {
//                   array_push($quotationItemIds, $quotationItem->id);
//                    // At least one match...
//                    $quotationSubItemKeys[$quotationItem->id] = $arrayKeys;
//                }
////               check this model line is existing in addons array, if yes get the array key;
//            }
//            $isVehicle = 0;
        }

//        foreach ($quotationItemIds as $itemId) {
//            $itemKeys = $quotationSubItemKeys[$itemId];
//            foreach ($itemKeys as $itemKey) {
//                if($request->types[$itemKey] == 'ModelLine' ) {
//                     $alreadyaddedquotationIds = QuotationSubItem::where('quotation_id', $quotation->id)
//                         ->pluck('quotation_item_id')->toArray();
//                    if($request->is_addon[$itemKey] == 1) {
//
//                        $quotationItemRow = QuotationItem::where('quotation_id', $quotation->id)
//                            ->where('reference_id', $request->reference_ids[$itemKey])
//                            ->where('reference_type', 'App\Models\MasterModelLines')
//                            ->whereNotIn('id', $alreadyaddedquotationIds)
//                            ->where('is_addon', true)
//                            ->first();
//                    }
//                }else if($request->types[$itemKey] == 'Accessory' || $request->types[$itemKey] == 'SparePart' || $request->types[$itemKey] == 'Kit') {
//
//                    $quotationItemRow = QuotationItem::where('quotation_id', $quotation->id)
//                        ->where('reference_id', $request->reference_ids[$itemKey])
//                        ->where('reference_type', 'App\Models\AddonDetails')
//                        ->whereNotIn('id', $alreadyaddedquotationIds)
//                        ->first();
//                }
//                if($quotationItemRow) {
//                    $quotationSubItem = new QuotationSubItem();
//                    $quotationSubItem->quotation_id = $quotation->id;
//                    $quotationSubItem->quotation_item_parent_id = $itemId;
//                    $quotationSubItem->quotation_item_id = $quotationItemRow->id;
//                    $quotationSubItem->save();
//
//                }
//            }
//        }
        DB::commit();
        $quotationDetail = QuotationDetail::with('country')->find($quotationDetail->id);

//        $quotation = Quotation::find(26);
//        $call = Calls::find($quotation->calls_id);
//        $quotationDetail = QuotationDetail::with('country')->where('quotation_id', 26)->orderBy('id','DESC')->first();

        $vehicles =  QuotationItem::where("reference_type", 'App\Models\Varaint')
            ->where('quotation_id', $quotation->id)->get();

        $otherVehicles = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('is_enable', true)
            ->where('is_addon', false)
            ->get();

        $variants = QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->where('is_addon', false)->get();

//        $alreadyAddedQuotationIds = QuotationSubItem::where('quotation_id', $quotation->id)
//                         ->pluck('quotation_item_id')->toArray();
        $directlyAddedAddons =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
//            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
//        $hidedDirectlyAddedAddonSum =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
//            ->where('quotation_id', $quotation->id)
//            ->whereNotIn('id', $alreadyAddedQuotationIds)
//            ->where('is_enable', false)
//            ->where('is_addon', true)
//            ->sum('total_amount');

        $addons = QuotationItem::where('reference_type','App\Models\AddonDetails')
//            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $OtherAddons = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();

//        $hidedAddonSum = QuotationItem::where('reference_type','App\Models\AddonDetails')
//            ->whereNotIn('id', $alreadyAddedQuotationIds)
//            ->where('is_enable', true)
//            ->where('quotation_id', $quotation->id)
//            ->where('is_enable', false)->sum('total_amount');

//        $addonsTotalAmount = $hidedDirectlyAddedAddonSum + $hidedAddonSum;

        $shippingCharges = QuotationItem::where('reference_type','App\Models\Shipping')
//            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingDocuments = QuotationItem::where('reference_type','App\Models\ShippingDocuments')
//            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $otherDocuments = QuotationItem::where('reference_type','App\Models\OtherLogisticsCharges')
//            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingCertifications = QuotationItem::where('reference_type','App\Models\ShippingCertification')
//            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();

        $salesPersonDetail = EmployeeProfile::where('user_id', Auth::id())->first();

        $data = [];
        $data['sales_person'] = Auth::user()->name;
        $data['sales_office'] = 'Central 191';
        $data['sales_phone'] = '';
        $data['sales_email'] = Auth::user()->email;
        $data['client_id'] = $call->id;
        $data['client_email'] = $call->email;
        $data['client_name'] = $call->name;
        $data['client_phone'] = $call->phone;
        $data['client_address'] = $call->address;
        $data['document_number'] = $quotation->id;
        $data['company'] = $call->company_name;

        $data['document_date'] = Carbon::parse($quotation->date)->format('M d,Y');
        if($salesPersonDetail) {
            $data['sales_office'] = $salesPersonDetail->office;
            $data['sales_phone'] = $salesPersonDetail->contact_number;
        }
        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
//        $shippingHidedItemAmount = QuotationItem::where('is_enable', false)
//            ->where('quotation_id', $quotation->id)
//            ->whereIn('reference_type',['App\Models\ShippingDocuments','App\Models\Shipping',
//                'App\Models\ShippingCertification','App\Models\OtherLogisticsCharges'])
//            ->sum('total_amount');
//        $vehicleCount = $vehicles->count() + $variants->count();
//        if($vehicleCount > 0) {
//            $shippingChargeDistriAmount = $shippingHidedItemAmount / $vehicleCount;
//        }else{
//            $shippingChargeDistriAmount = 0;
//        }

//        return view('proforma.proforma_invoice', compact('quotation','data','quotationDetail','aed_to_usd_rate','aed_to_eru_rate',
//            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications','variants','directlyAddedAddons','addonsTotalAmount'));
        $pdfFile = Pdf::loadView('proforma.proforma_invoice', compact('quotation','data','quotationDetail','aed_to_usd_rate','aed_to_eru_rate',
            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications','variants','directlyAddedAddons',
        'otherVehicles','OtherAddons'));
//        return $pdfFile->stream('test.pdf');
        $filename = 'quotation_'.$quotation->id.'.pdf';
        $generatedPdfDirectory = public_path('Quotations');
        $directory = public_path('storage/quotation_files');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdfFile->save($generatedPdfDirectory . '/' . $filename);

        $pdf = $this->pdfMerge($quotation->id);
        $file = 'Quotation_'.$quotation->id.'_'.date('Y_m_d').'.pdf';
        $pdf->Output($directory.'/'.$file,'F');
        $quotation->file_path = 'quotation_files/'.$file;
        $quotation->save();

        return redirect()->route('dailyleads.index',['quotationFilePath' => $file])->with('success', 'Quotation created successfully.');
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
        $quotation = Quotation::updateOrCreate([
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
    public function getShippingPort(Request $request) {
        $shippingPorts = MasterShippingPort::where('country_id', $request->country_id)
                         ->get();

        return $shippingPorts;
    }
    public function getShippingCharges(Request $request) {
        $shippingCharges = Shipping::with('shippingMedium')
                            ->where('from_port', $request->shipping_port_id)
                            ->get();
        return $shippingCharges;
    }
}
