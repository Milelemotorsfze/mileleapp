<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\UserActivities;
use App\Models\Clients;
use App\Models\LeadSource;
use App\Models\ClientLeads;
use App\Models\CallsRequirement;
use App\Models\Logs;
use App\Models\AddonDetails;
use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use App\Models\AgentCommission;
use App\Models\Country;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\MasterShippingPorts;
use App\Models\OtherLogisticsCharges;
use App\Models\MuitlpleAgentSystemCode;
use App\Models\Quotation;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\QuotationClient;
use App\Models\QuotationDetail;
use App\Models\QuotationItem;
use App\Models\QuotationSubItem;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shipping;
use App\Models\ShippingCertification;
use App\Models\ShippingDocuments;
use App\Models\Varaint;
use App\Models\QuotationVins;
use App\Models\Vehicles;
use App\Models\Vehiclescarts;
use App\Models\MasterModelLines;
use App\Models\CartAddon;
use App\Models\So;
use App\Models\MuitlpleAgents;
use App\Models\Soitems;
use App\Models\BookingRequest;
use Barryvdh\DomPDF\Facade;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Monarobase\CountryList\CountryListFacade;
use Illuminate\Support\Facades\Log;

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
        if (!in_array($request->nature_of_deal, ['regular_deal', 'letter_of_credit'])) {
            return redirect()->back()->with('error', 'Invalid Nature of Deal selected.')->withInput();
        }  

        $request->validate([
            'nature_of_deal' => 'required|in:regular_deal,letter_of_credit',
        ], [
            'nature_of_deal.required' => 'Please select the Nature of Deal.',
            'nature_of_deal.in' => 'Invalid selection for Nature of Deal.',
        ]);      

        $agentsmuiltples = 0;
        $systemcode = $request->system_code_amount;
        $separatedValues = [];
        if($request->agents_id)
        {
        foreach ($systemcode as $code) {
            if (strpos($code, '/') !== false) {
                $agentsmuiltples = 1;
                $values = explode('/', $code);
                $sum = array_sum($values);
                $separatedValues[] = $sum;
            } else {
                $separatedValues[] = (int)$code;
            }
        }
    }
        $agentsin = 0;
        $isVehicle = 0;
        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
        DB::beginTransaction();
        $call = Calls::find($request->calls_id);
        if($request->shipping_method == "CNF")
        {
            $call->type = "Local";    
        }
        else
        {
            $call->type = "Export"; 
        }
        $call->status = 'Quoted';
        $call->save();
        $call->company_name = $request->company_name;
        $call->name = $request->name;
        $call->phone = $request->phone;
        $call->email = $request->email;
        $call->client_contact_person = $request->contact_person;
        $call->address = $request->address;
        $call->save();
        $quotation = new Quotation();
        if($request->currency == 'AED') {
            $quotation->deal_value = $request->total;
        }else{
            $quotation->deal_value = $request->deal_value;
        }
        $quotation->sales_notes = $request->remarks;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        if (!$hasPermission) {
        $quotation->created_by = Auth::id();
        }
        else
        {
        $quotation->created_by = $request->salespersons;   
        }
        $quotation->calls_id = $request->calls_id;
        $quotation->currency = $request->currency;
        $quotation->document_type = $request->document_type;
        $quotation->nature_of_deal = $request->nature_of_deal;
        $quotation->third_party_payment = $request->thirdpartypayment;
        $quotation->date = Carbon::now();
        if($request->document_type == 'Proforma') {
            $quotation->document_type = 'Proforma Invoice';
        }
        $quotation->shipping_method = $request->shipping_method;
        $quotation->save();
        $agentsId = $request->agents_id;
            if (!isset($agentsId) || empty($agentsId)) {
                // Handle the case where no agent ID is provided or it's empty
            } 
            else {
                $agentIdsArray = explode(',', $agentsId);
                $agentsCount = count($agentIdsArray);
                if ($agentsCount == 1) {
                    $agentsId = $agentIdsArray[0];
                } 
                else {
                    $agentsin = 1;
                    foreach ($agentIdsArray as $agentId) {
                        $multipleAgent = new MuitlpleAgents();
                        $multipleAgent->agents_id = $agentId;
                        $multipleAgent->quotations_id = $quotation->id;
                        $multipleAgent->save();
                    }
                    $agentsId = end($agentIdsArray);
                }
            }
        $quotationDetail = new QuotationDetail();
        $quotationDetail->quotation_id  = $quotation->id;
        $quotationDetail->country_id  = $request->country_id;
        $quotationDetail->delivery_country  = $request->countryofdischarge;
        $quotationDetail->incoterm  = $request->incoterm;
        $quotationDetail->shipping_port_id   = $request->from_shipping_port_id;
        $quotationDetail->to_shipping_port_id   = $request->to_shipping_port_id;
        $quotationDetail->place_of_supply  = $request->place_of_supply;
        $quotationDetail->document_validity  = $request->document_validity;
        $quotationDetail->payment_terms  = $request->payment_terms;
        $quotationDetail->representative_name = $request->representative_name;
        $quotationDetail->representative_number = $request->representative_number;
        $quotationDetail->cb_name = $request->selected_cb_name;
        $quotationDetail->cb_number = $request->cb_number;
        if($agentsin == 1)
        {
            $quotationDetail->muitlple_agents_id = $multipleAgent->id;
        }
        $quotationDetail->agents_id = $agentsId;
        $quotationDetail->advance_amount = $request->advance_amount;
        $quotationDetail->due_date = $request->due_date;
        $quotationDetail->selected_bank = $request->select_bank;
        if($request->agents_id) {
            $agentCommission = new AgentCommission();
            $agentCommission->commission = $request->system_code ?? '';
            $agentCommission->status = 'Quotation';
            $agentCommission->agents_id  =  $request->agents_id ?? '';
            $agentCommission->quotation_id  = $quotation->id;
            $agentCommission->created_by = Auth::id();
            $agentCommission->save();
        }
        $commissionAED = 0;
        $quotationItemIds = [];
        $quotationItemsArray = [];
        foreach ($request->prices as $key => $price) {
            $item = "";
        if($request->agents_id)
        {
            if($request->system_code_currency[$key] == 'U') { 
                $amount = $separatedValues[$key] * $aed_to_usd_rate->value;
            }else{
                $amount = $separatedValues[$key];
            }
           $commissionAED = $commissionAED + $amount;
        }
           $quotationItem = new QuotationItem();
           $quotationItem->unit_price = $price;
           $quotationItem->quantity = $request->quantities[$key];
           $quotationItem->description = $request->descriptions[$key];
           $quotationItem->total_amount = $request->total_amounts[$key];
           if($request->agents_id)
           {
           $quotationItem->system_code_amount = $separatedValues[$key];
           $quotationItem->system_code_currency = $request->system_code_currency[$key];
           }
           $quotationItem->quotation_id = $quotation->id;
           $quotationItem->uuid = $request->uuids[$key];
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
               if($request->reference_ids[$key] != 'Other')
               {
                   $item = Varaint::find($request->reference_ids[$key]);
                   
               }
               $isVehicle = 1;
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
            //    info($quotationItem->brand_id);
           }else if($request->types[$key] == 'Other') {
               $item = OtherLogisticsCharges::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'ModelLine') {
               $item = MasterModelLines::find($request->reference_ids[$key]);
                  $isVehicle = 1;

               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];

           }else if($request->types[$key] == 'Brand') {
               $item = Brand::find($request->reference_ids[$key]);
                 $isVehicle = 1;

               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];

           } else if($request->types[$key] == 'Accessory' || $request->types[$key] == 'SparePart' || $request->types[$key] == 'Kit') {

               $item = AddonDetails::find($request->reference_ids[$key]);

               $quotationItem->addon_type = $request->addon_types[$key];
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
               $quotationItem->model_description_id = $request->model_description_ids[$key];
           }else if($request->types[$key] == 'Addon') {
               if($request->reference_ids[$key] != 'Other') {

                   $item = Addon::find($request->reference_ids[$key]);
               }
               $quotationItem->addon_type = $request->addon_types[$key];
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
               $quotationItem->model_description_id = $request->model_description_ids[$key];
           }
            if($item) {
                $quotationItem->reference()->associate($item);

            }
            $quotationItem->save();
            if($isVehicle == 1){
                if ($request->uuids[$key]) {
                    $vinArray = explode(',', $request->vinnumbers[$key]);
                    foreach ($vinArray as $vin) {
                        if ($vin !== "undefined" && $vin !== null && !empty($vin)) {
                    $vinupdate = New QuotationVins();
                    $vinupdate->quotation_items_id = $quotationItem->id;
                    $vinupdate->vin =$vin;
                    $vinupdate->save();
                    $vinupdate->vin = trim(strtolower($vinupdate->vin));
                    $vehiclesvins = Vehicles::where('vin', $vinupdate->vin)->first();
                    if($vehiclesvins){
                    $booking = New BookingRequest();
                    $booking->date = now()->toDateString();
                    $booking->vehicle_id = $vehiclesvins->id;
                    $booking->calls_id = $request->calls_id; 
                    $booking->created_by =  Auth::id();
                    $booking->status = 'New';
                    $booking->days = '3';
                    $booking->quotation_items_id = $quotationItem->id;
                    $booking->quotations_id = $quotation->id;
                    $booking->save();
                    }
                    }
                }
                   array_push($quotationItemIds, $quotationItem->id);
                }
            }
            $isVehicle = 0; 
            $quotationItemsArray[] = $quotationItem;
        }
        if ($request->agents_id) {
            foreach ($quotationItemsArray as $index => $quotationItem) {
                $code = $systemcode[$index]; // Get the corresponding systemcode for the current quotationItem
                if (strpos($code, '/') !== false) {
                    $agentsmuiltples = 1;
                    $values = explode('/', $code);
                    foreach ($values as $value) {
                        $muitlpleagentsystemcode = new MuitlpleAgentSystemCode();
                        $muitlpleagentsystemcode->system_code = $value;
                        $muitlpleagentsystemcode->quotation_items_id = $quotationItem->id;
                        $muitlpleagentsystemcode->save();
                    }
                }
            }
        }        
        $quotationDetail->system_code = $commissionAED;
        $quotationDetail->save(); 
        foreach ($quotationItemIds as $itemId) {
            $quotationItemRow = QuotationItem::find($itemId);
            $subItemIds = QuotationItem::where('uuid', $quotationItemRow->uuid)
                                    ->whereNot('id',$quotationItemRow->id)
                                    ->whereNotNull('uuid')
                                    ->where('quotation_id', $quotation->id)->pluck('id')->toArray();
            if($subItemIds) {
                foreach ($subItemIds as $subItemId) {
                    $quotationSubItem = new QuotationSubItem();
                    $quotationSubItem->quotation_id = $quotation->id;
                    $quotationSubItem->quotation_item_parent_id = $itemId;
                    $quotationSubItem->quotation_item_id = $subItemId;
                    $quotationSubItem->save();
                }
            }

        }
        DB::commit();
        $quotationDetail = QuotationDetail::with('country')->find($quotationDetail->id);
        $vehicles =  QuotationItem::where("reference_type", 'App\Models\Varaint')
            ->where('quotation_id', $quotation->id)->get();
        $otherVehicles = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', false)
            ->get();
        $vehicleWithBrands = QuotationItem::where('quotation_id', $quotation->id)
                ->whereIn("reference_type", ['App\Models\Brand','App\Models\MasterModelLines'])
                ->where('is_addon', false)
                ->get();
        $alreadyAddedQuotationIds = QuotationSubItem::where('quotation_id', $quotation->id)
                         ->pluck('quotation_item_id')->toArray();
        $directlyAddedAddons =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedDirectlyAddedAddonSum =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', false)
            ->where('is_addon', true)
            ->sum('total_amount');
        $addons = QuotationItem::whereIn('reference_type',['App\Models\AddonDetails','App\Models\Addon'])
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $OtherAddons = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedAddonSum = QuotationItem::where('reference_type','App\Models\AddonDetails')
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', false)->sum('total_amount');
        $addonsTotalAmount = $hidedDirectlyAddedAddonSum + $hidedAddonSum;
        $shippingCharges = QuotationItem::where('reference_type','App\Models\Shipping')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingDocuments = QuotationItem::where('reference_type','App\Models\ShippingDocuments')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $otherDocuments = QuotationItem::where('reference_type','App\Models\OtherLogisticsCharges')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingCertifications = QuotationItem::where('reference_type','App\Models\ShippingCertification')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $salesPersonDetail = EmployeeProfile::where('user_id', $quotation->created_by)->first();
        $salespersonqu = User::find($quotation->created_by);
        $data = [];
        $data['sales_person'] = $salespersonqu->name ?? '';
        $data['sales_office'] = 'Central 191';
        $data['sales_phone'] = '';
        $data['sales_email'] = $salespersonqu->email ?? '';
        $data['client_id'] = $call->id;
        $data['client_email'] = $call->email;
        $data['client_name'] = $call->name;
        $data['client_contact_person'] = $call->client_contact_person ?? '';
        $data['client_phone'] = $call->phone;
        $data['client_address'] = $call->address ?? '';
        $data['document_number'] = $quotation->id;
        $data['company'] = $call->company_name;
        $data['document_date'] = Carbon::parse($quotation->date)->format('M d,Y');
        if($salesPersonDetail) {
            $data['sales_office'] = $salesPersonDetail->location->name ?? '';
            $data['sales_phone'] = $salesPersonDetail->contact_number ?? '';
        }
        $shippingHidedItemAmount = QuotationItem::where('is_enable', false)
            ->where('quotation_id', $quotation->id)
            ->whereIn('reference_type',['App\Models\ShippingDocuments','App\Models\Shipping',
                'App\Models\ShippingCertification','App\Models\OtherLogisticsCharges'])
            ->sum('total_amount');
        $vehicleCount = $vehicles->count() + $otherVehicles->count() + $vehicleWithBrands->count();

        if($vehicleCount > 0) {
            $shippingChargeDistriAmount = $shippingHidedItemAmount / $vehicleCount;
        }else{
            $shippingChargeDistriAmount = 0;
        }
        $quotationid = $quotation->id;
        $multiplecp = MuitlpleAgents::where('quotations_id', $quotationid)->where('agents_id', '!=', $quotationDetail->agents_id)->get();
        $pdfFile = Pdf::loadView('proforma.proforma_invoice', compact('multiplecp','quotation','data','quotationDetail','aed_to_usd_rate','aed_to_eru_rate',
            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications','directlyAddedAddons','addonsTotalAmount',
        'otherVehicles','vehicleWithBrands','OtherAddons','shippingChargeDistriAmount'));
        $filename = 'quotation_'.$quotation->id.'.pdf';
        $generatedPdfDirectory = public_path('Quotations');
        $directory = public_path('storage/quotation_files');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdfFile->save($generatedPdfDirectory . '/' . $filename);
        $pdf = $this->pdfMerge($quotation->id);
        $file = 'Quotation_'.$quotation->id.'_'.date('Y_m_d_H_i_s').'.pdf';
        $pdf->Output($directory.'/'.$file,'F');
        $quotation->file_path = 'quotation_files/'.$file; 
        $quotation->save();
        $uniqueString = Str::random(10);
        $timestamp = now()->timestamp;
        $uniqueNumber = $uniqueString . $timestamp;
        $signatureLink = config('app.url') .'clientsignature/' . $uniqueNumber . '/' . $quotation->id;
        $newsignatures = Quotation::find($quotation->id);
        $newsignatures->signature_link = $signatureLink;
        $newsignatures->save();
        return redirect()->route('dailyleads.index',['quotationFilePath' => $file])->with('success', 'Quotation created successfully.');
    }
    public function pdfMerge($quotationId)
    {
       
        $quotation = Quotation::find($quotationId);
        $filename = 'quotation_'.$quotationId.'.pdf';

        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

        $pdf->setPrintHeader(false);
        $files[] = 'Quotations/'.$filename;
        if($quotation->third_party_payment === "Yes")
        {
            $files[] = public_path('Quotations/quotation_attachment_documents.pdf');
        }
        else
        {
            $files[] = 'Quotations/quotation_attachment_documents.pdf';
        }
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
   
    $qoutationid = request()->input('quotationid');
    $agentsmuiltples = 0;
    $systemcode = $request->system_code_amount;
    $separatedValues = [];
    if($request->agents_id)
    {
    foreach ($systemcode as $code) {
        if (strpos($code, '/') !== false) {
            $agentsmuiltples = 1;
            $values = explode('/', $code);
            $sum = array_sum($values);
            $separatedValues[] = $sum;
        } else {
            $separatedValues[] = (int)$code;
        }
    }
    }
    $agentsin = 0;
    $isVehicle = 0;
    $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
    $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
    DB::beginTransaction();
    $call = Calls::find($request->calls_id);
    $call->status = 'Quoted';
    $call->company_name = $request->company_name;
    $call->name = $request->name;
    $call->phone = $request->phone;
    $call->email = $request->email;
    $call->address = $request->address;
    $call->save();
    $quotation = Quotation::find($qoutationid);
    if($request->currency == 'AED') {
        $quotation->deal_value = $request->total;
    }else{
        $quotation->deal_value = $request->deal_value;
    }
    $quotation->sales_notes = $request->remarks;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
    if ($hasPermission)
    {
    $quotation->created_by = $request->salespersons;
    }
    else
    {
        $quotation->created_by = Auth::id(); 
    }
    $quotation->calls_id = $request->calls_id;
    $quotation->currency = $request->currency;
    $quotation->document_type = $request->document_type;
    $quotation->nature_of_deal = $request->nature_of_deal;
    $quotation->third_party_payment = $request->thirdpartypayment;
    $quotation->date = Carbon::now();
    if($request->document_type == 'Proforma') {
        $quotation->document_type = 'Proforma Invoice';
    }
    $quotation->shipping_method = $request->shipping_method;
    $quotation->save();
    $agentsId = $request->agents_id;
    $lastAgentId = null;
    if (!isset($agentsId) || empty($agentsId)) {
        // Handle the case where $agentsId is not set or is empty.
    } else {
        $agentIdsArray = explode(',', $agentsId);
        $agentsCount = count($agentIdsArray);
        $existingAgentIds = [];
        if ($agentsCount == 1) {
        foreach ($agentIdsArray as $agentId) {
            $agentsin = 1;
            $multipleAgent = MuitlpleAgents::updateOrCreate(
                ['agents_id' => $agentId, 'quotations_id' => $quotation->id],
                ['agents_id' => $agentId, 'quotations_id' => $quotation->id]
            );
            $multipleAgentId = $multipleAgent->id;
            $existingAgentIds[] = $agentId;
            $lastAgentId = $agentId;
        }
    }
        // Find and delete agents that are not in the $agentIdsArray
        MuitlpleAgents::where('quotations_id', $quotation->id)
            ->whereNotIn('agents_id', $existingAgentIds)
            ->delete();
    }        
    $quotationDetail = QuotationDetail::where('quotation_id', $qoutationid)->first();
    if ($quotationDetail) {
    $quotationDetail->quotation_id  = $quotation->id;
        $quotationDetail->country_id  = $request->country_id;
        $quotationDetail->delivery_country  = $request->countryofdischarge;
        $quotationDetail->incoterm  = $request->incoterm;
        $quotationDetail->shipping_port_id   = $request->from_shipping_port_id;
        $quotationDetail->to_shipping_port_id   = $request->to_shipping_port_id;
        $quotationDetail->place_of_supply  = $request->place_of_supply;
        $quotationDetail->document_validity  = $request->document_validity;
        $quotationDetail->payment_terms  = $request->payment_terms;
        $quotationDetail->representative_name = $request->representative_name;
        $quotationDetail->representative_number = $request->representative_number;
        $quotationDetail->cb_name = $request->selected_cb_name;
        $quotationDetail->cb_number = $request->cb_number;
        if($agentsin == 1)
        {
            $quotationDetail->muitlple_agents_id = $multipleAgent->id;
            $quotationDetail->agents_id = $lastAgentId;
        }
        else
        {
            $quotationDetail->agents_id = $request->agents_id;
        }
        $quotationDetail->advance_amount = $request->advance_amount;
        $quotationDetail->due_date = $request->due_date;
        $quotationDetail->selected_bank = $request->select_bank;
        if($request->agents_id) {
            $agentCommission = new AgentCommission();
            $agentCommission->commission = $request->system_code ?? '';
            $agentCommission->status = 'Quotation';
            $agentCommission->agents_id  =  $request->agents_id ?? '';
            $agentCommission->quotation_id  = $quotation->id;
            $agentCommission->created_by = Auth::id();
            $agentCommission->save();
        }
        $commissionAED = 0;
        $quotationItemIds = [];
        $existingQuotationItems = QuotationItem::where('quotation_id', $qoutationid)->get();
        $soexisting = So::where('quotation_id', $qoutationid)->get();
        foreach ($existingQuotationItems as $quotationItem) {
            if(!in_array($quotationItem->id, $request->vehiclesitemsid)) {
                if ($soexisting->isNotEmpty()) {
                    $soitems = Soitems::where('quotation_items_id', $quotationItem->id)->get();
                    if ($soitems->isNotEmpty()) {
                        foreach ($soitems as $soitem) {
                            $vehicle = Vehicles::find($soitem->vehicles_id);
                            if ($vehicle) {
                                $soId = $vehicle->so_id;
                                $vehicle->so_id = null;
                                \Log::info('Unassign SO id - Case 1-'.$soId);
                                $vehicle->save();
                                \Log::info('SO items deleted - Case 1-'.$soId);
                                Soitems::where('vehicles_id', $vehicle->id)->update(['deleted_by' => Auth::id()]);
                                Soitems::where('vehicles_id', $vehicle->id)->delete();

                            }
                        }
                    }
                }
                $existingSubItemd = QuotationSubItem::where('quotation_id', $qoutationid)
                ->where('quotation_item_parent_id', $quotationItem->id)
                ->get(); 
                if ($existingSubItemd->isNotEmpty()) {
                    foreach ($existingSubItemd as $existingSubItem) {
                        $existingSubItem->delete();
                    }
                }
                $existingbooking = BookingRequest::where('quotation_items_id', $quotationItem->id)->where('quotations_id', $qoutationid)->get();
                if ($existingbooking->isNotEmpty()) {
                    foreach ($existingbooking as $existingbookings) {
                        $existingapproved = Booking::where('booking_requests_id', $existingbooking->id)->first();
                        if($existingapproved)
                        {
                            $existingapproved->booking_end_date = now()->toDateString();
                            $existingapproved->save();
                            $vehicle = Vehicles::find($existingapproved->vehicle_id);
                            if ($vehicle) {
                                $vehicle->reservation_end_date = now()->toDateString();
                                $vehicle->save();
                            }
                        }
                        else
                        {
                        $existingbookings->delete();
                        }
                    }
                }
                $quotationItem->delete();
            }
            else {
                $key = array_search($quotationItem->id, $request->vehiclesitemsid);
                if ($quotationItem->quantity != $request->quantities[$key]) {
                $quantityDifference = $quotationItem->quantity - $request->quantities[$key];
                if ($soexisting->isNotEmpty()) {
                    $soitems = Soitems::where('quotation_items_id', $quotationItem->id)->get();
                    if ($soitems->isNotEmpty()) {
                        $vehicles = [];
                        foreach ($soitems as $soitem) {
                            $vehicle = Vehicles::find($soitem->vehicles_id);
                            if ($vehicle) {
                                $vehicles[] = $vehicle;
                            }
                        }
                        $vehiclesToDelete = array_slice($vehicles, 0, $quantityDifference);
                        foreach ($vehiclesToDelete as $vehicle) {
                            $soId = $vehicle->so_id;
                            $vehicle->so_id = null;
                            \Log::info('Unassign SO id - Case 2-'.$soId);
                            $vehicle->save();
                            \Log::info('SO items deleted - Case 2-'.$soId);
                            Soitems::where('vehicles_id', $vehicle->id)->update(['deleted_by' => Auth::id()]);
                            Soitems::where('vehicles_id', $vehicle->id)->delete();
                        }
                    }
                }
                $existingBookings = BookingRequest::where('quotation_items_id', $quotationItem->id)
                ->where('quotations_id', $qoutationid)
                ->get();
                foreach ($existingBookings as $existingBooking) {
                    $vinarrys = explode(',', $request->vinnumbers);
                    if ($vin !== "undefined" && $vin !== null && !empty($vin)) {
                        $vehicleIds = Vehicles::whereIn('vin', $vinarrys)->pluck('id')->toArray();
                    }                    
                    $existingApproved = Booking::where('booking_requests_id', $existingBooking->id)->whereNotIn('vehicle_id', $vehicleIds)->first();
                    if ($existingApproved) {
                        $quantityDifference = $quotationItem->quantity - $request->quantities[$key];
                        $vehiclesToUpdate = min($quantityDifference, $existingApproved->quantity);
                        if ($vehiclesToUpdate > 0) {
                            $existingApproved->booking_end_date = now()->toDateString();
                            $existingApproved->save();
                            $vehicle = Vehicles::find($existingApproved->vehicle_id);
                            if ($vehicle) {
                                $vehicle->reservation_end_date = now()->toDateString();
                                $vehicle->save();
                            }
                        }
                    } else {
                        $existingBooking->delete();
                    }
            }            
            }
        }
    }
        foreach ($request->prices as $key => $price) {
            $item = "";
            if($request->agents_id)
            {
                if($request->system_code_currency[$key] == 'U') { 
                    $amount = $separatedValues[$key] * $aed_to_usd_rate->value;
                }else{
                    $amount = $separatedValues[$key];
                }
               $commissionAED = $commissionAED + $amount;
            }
           if(isset($request->vehiclesitemsid[$key])) {
            $quotationItem = QuotationItem::find($request->vehiclesitemsid[$key]);
            } else {
                $quotationItem = new QuotationItem();
            }
           $quotationItem->unit_price = $price;
           $quotationItem->quantity = $request->quantities[$key];
           $quotationItem->description = $request->descriptions[$key];
           $quotationItem->total_amount = $request->total_amounts[$key];
           if($request->agents_id)
           {
           $quotationItem->system_code_amount = $separatedValues[$key];
           $quotationItem->system_code_currency = $request->system_code_currency[$key];
           }
           $quotationItem->quotation_id = $qoutationid;
           $quotationItem->uuid = $request->uuids[$key];
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
               if($request->reference_ids[$key] != 'Other')
               {
                   $item = Varaint::find($request->reference_ids[$key]);
                   
               }
               $isVehicle = 1;
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
           }else if($request->types[$key] == 'Other') {
               $item = OtherLogisticsCharges::find($request->reference_ids[$key]);

           }else if($request->types[$key] == 'ModelLine') {
               $item = MasterModelLines::find($request->reference_ids[$key]);
                  $isVehicle = 1;

               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];

           }else if($request->types[$key] == 'Brand') {
               $item = Brand::find($request->reference_ids[$key]);
                 $isVehicle = 1;

               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];

           } else if($request->types[$key] == 'Accessory' || $request->types[$key] == 'SparePart' || $request->types[$key] == 'Kit') {
               $item = AddonDetails::find($request->reference_ids[$key]);
               $quotationItem->addon_type = $request->addon_types[$key];
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
           }else if($request->types[$key] == 'Addon') {
               if($request->reference_ids[$key] != 'Other') {
                   $item = Addon::find($request->reference_ids[$key]);
               }
               $quotationItem->addon_type = $request->addon_types[$key];
               $quotationItem->brand_id = $request->brand_ids[$key];
               $quotationItem->model_line_id = $request->model_line_ids[$key];
           }
            if($item && !isset($request->vehiclesitemsid[$key])) {
                $quotationItem->reference()->associate($item);
            }
            $quotationItem->save();
            if($isVehicle == 1){ 
                if ($request->uuids[$key]) {
                    $vinArray = explode(',', $request->vinnumbers[$key]);
                    QuotationVins::where('quotation_items_id', $quotationItem->id)->delete();
                    foreach ($vinArray as $vin) {
                    if ($vin !== "undefined" && $vin !== null && !empty($vin)) {
                    $vinupdate = New QuotationVins();
                    $vinupdate->quotation_items_id = $quotationItem->id;
                    $vinupdate->vin =$vin;
                    $vinupdate->save();
                    $vinupdate->vin = trim(strtolower($vinupdate->vin));
                    $vehiclesvins = Vehicles::where('vin', $vinupdate->vin)->first();
                    if($vehiclesvins){
                    $existingbooking = BookingRequest::where('vehicle_id', $vehiclesvins->id)->where('quotations_id', $qoutationid)->first();
                    if(!$existingbooking){
                    $booking = New BookingRequest();
                    $booking->date = now()->toDateString();
                    $booking->vehicle_id = $vehiclesvins->id;
                    $booking->calls_id = $request->calls_id; 
                    $booking->created_by =  Auth::id();
                    $booking->status = 'New';
                    $booking->days = '3';
                    $booking->quotation_items_id = $quotationItem->id;
                    $booking->quotations_id = $qoutationid;
                    $booking->save();
                    }
                }
                }
                }
                   array_push($quotationItemIds, $quotationItem->id);
                }
            }
            $isVehicle = 0;
            $quotationItemsArray[] = $quotationItem;
        }
        if ($request->agents_id) {
            foreach ($quotationItemsArray as $index => $quotationItem) {
                $code = $systemcode[$index]; // Get the corresponding systemcode for the current quotationItem
                if (strpos($code, '/') !== false) {
                    $agentsmuiltples = 1;
                    $values = explode('/', $code);
                    foreach ($values as $value) {
                        $muitlpleagentsystemcode = new MuitlpleAgentSystemCode();
                        $muitlpleagentsystemcode->system_code = $value;
                        $muitlpleagentsystemcode->quotation_items_id = $quotationItem->id;
                        $muitlpleagentsystemcode->save();
                    }
                }
            }
        }             
        $quotationDetail->system_code = $commissionAED;
        $quotationDetail->save();
        foreach ($quotationItemIds as $itemId) {
            $quotationItemRow = QuotationItem::find($itemId);
            $subItemIds = QuotationItem::where('uuid', $quotationItemRow->uuid)
                                    ->whereNot('id',$quotationItemRow->id)
                                    ->whereNotNull('uuid')
                                    ->where('quotation_id', $quotation->id)->pluck('id')->toArray();
            if($subItemIds) {
                foreach ($subItemIds as $subItemId) {
                    $existingSubItem = QuotationSubItem::where('quotation_id', $quotation->id)
                                ->where('quotation_item_parent_id', $itemId)
                                ->where('quotation_item_id', $subItemId)
                                ->exists();
            if (!$existingSubItem) {
                    $quotationSubItem = new QuotationSubItem();
                    $quotationSubItem->quotation_id = $quotation->id;
                    $quotationSubItem->quotation_item_parent_id = $itemId;
                    $quotationSubItem->quotation_item_id = $subItemId;
                    $quotationSubItem->save();
            }
                }
            }
        }
        DB::commit();
        $quotationDetail = QuotationDetail::with('country')->find($quotationDetail->id);
        $vehicles =  QuotationItem::where("reference_type", 'App\Models\Varaint')
            ->where('quotation_id', $quotation->id)->get();
        $otherVehicles = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', false)
            ->get();
        $vehicleWithBrands = QuotationItem::where('quotation_id', $quotation->id)
                ->whereIn("reference_type", ['App\Models\Brand','App\Models\MasterModelLines'])
                ->where('is_addon', false)
                ->get();
        $alreadyAddedQuotationIds = QuotationSubItem::where('quotation_id', $quotation->id)
                         ->pluck('quotation_item_id')->toArray();
        $directlyAddedAddons =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedDirectlyAddedAddonSum =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', false)
            ->where('is_addon', true)
            ->sum('total_amount');
        $addons = QuotationItem::whereIn('reference_type',['App\Models\AddonDetails','App\Models\Addon'])
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $OtherAddons = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedAddonSum = QuotationItem::where('reference_type','App\Models\AddonDetails')
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', false)->sum('total_amount');
        $addonsTotalAmount = $hidedDirectlyAddedAddonSum + $hidedAddonSum;
        $shippingCharges = QuotationItem::where('reference_type','App\Models\Shipping')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingDocuments = QuotationItem::where('reference_type','App\Models\ShippingDocuments')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $otherDocuments = QuotationItem::where('reference_type','App\Models\OtherLogisticsCharges')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingCertifications = QuotationItem::where('reference_type','App\Models\ShippingCertification')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $salesPersonDetail = EmployeeProfile::where('user_id', $quotation->created_by)->first();
        $salespersonqu = User::find($quotation->created_by);
        $data = [];
        $data['sales_person'] = $salespersonqu->name ?? '';
        $data['sales_office'] = 'Central 191';
        $data['sales_phone'] = '';
        $data['sales_email'] = $salespersonqu->email ?? '';
        $data['client_id'] = $call->id;
        $data['client_email'] = $call->email;
        $data['client_name'] = $call->name;
        $data['client_contact_person'] = $call->client_contact_person ?? '';
        $data['client_phone'] = $call->phone;
        $data['client_address'] = $call->address;
        $data['document_number'] = $quotation->id;
        $data['company'] = $call->company_name;
        $data['document_date'] = Carbon::parse($quotation->date)->format('M d,Y');
        if($salesPersonDetail) {
            $data['sales_office'] = $salesPersonDetail->location->name ?? '';
            $data['sales_phone'] = $salesPersonDetail->contact_number ?? '';
        }
        $shippingHidedItemAmount = QuotationItem::where('is_enable', false)
            ->where('quotation_id', $quotation->id)
            ->whereIn('reference_type',['App\Models\ShippingDocuments','App\Models\Shipping',
                'App\Models\ShippingCertification','App\Models\OtherLogisticsCharges'])
            ->sum('total_amount');
        $vehicleCount = $vehicles->count() + $otherVehicles->count() + $vehicleWithBrands->count();
        if($vehicleCount > 0) {
            $shippingChargeDistriAmount = $shippingHidedItemAmount / $vehicleCount;
        }else{
            $shippingChargeDistriAmount = 0;
        }
        $quotationid = $quotation->id;
        $multiplecp = MuitlpleAgents::where('quotations_id', $quotationid)->where('agents_id', '!=', $quotationDetail->agents_id)->get();
        $pdfFile = Pdf::loadView('proforma.proforma_invoice', compact('multiplecp','quotation','data','quotationDetail','aed_to_usd_rate','aed_to_eru_rate',
            'vehicles','addons', 'shippingCharges','shippingDocuments','otherDocuments','shippingCertifications','directlyAddedAddons','addonsTotalAmount',
        'otherVehicles','vehicleWithBrands','OtherAddons','shippingChargeDistriAmount'));
        $filename = 'quotation_'.$quotation->id.'.pdf';
        $generatedPdfDirectory = public_path('Quotations');
        $directory = public_path('storage/quotation_files');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdfFile->save($generatedPdfDirectory . '/' . $filename);
        $pdf = $this->pdfMerge($quotation->id);
        $file = 'Quotation_'.$quotation->id.'_'.date('Y_m_d_H_i_s').'.pdf';
        $pdf->Output($directory.'/'.$file,'F');
        $quotation->file_path = 'quotation_files/'.$file; 
        $quotation->save();
        $uniqueString = Str::random(10);
        $timestamp = now()->timestamp;
        $uniqueNumber = $uniqueString . $timestamp;
        $signatureLink = config('app.url') .'clientsignature/' . $uniqueNumber . '/' . $quotation->id;
        $newsignatures = Quotation::find($quotation->id);
        $newsignatures->signature_link = $signatureLink;
        $newsignatures->signature_status = null;
        $newsignatures->save();
        return redirect()->route('dailyleads.index',['quotationFilePath' => $file])->with('success', 'Quotation Updated successfully.');
    }
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
        $shippingPorts = MasterShippingPorts::where('country_id', $request->country_id)
                         ->get();

        return $shippingPorts;
    }
    public function getShippingCharges(Request $request) {
      
        $shippingCharges =Shipping::with('shippingMedium')
                            ->where('from_port', $request->from_shipping_port_id)
                            ->where('to_port', $request->to_shipping_port_id)
                            ->get();
        return $shippingCharges;
    }
    public function getvinsqoutation(Request $request)
    {
        $callId = $request->input('callId');
        $quotations = Quotation::where('calls_id', $callId)->pluck('id');
        $response = [];
    
        foreach ($quotations as $quotation) {
            $quotationItems = QuotationItem::whereIn('reference_type', ['App\Models\Brand', 'App\Models\MasterModelLines', 'App\Models\Varaint'])
                ->where('quotation_id', $quotation)
                ->get();
    
            foreach ($quotationItems as $quotationItem) {
                $description = $quotationItem->description;
                $quotationVins = QuotationVins::where('quotation_items_id', $quotationItem->id)->get();
    
                if ($quotationVins->isNotEmpty()) {
                    $responseData = [
                        'description' => $description,
                        'quotationVins' => $quotationVins->toArray(),
                    ];
                    $response[] = $responseData;
                }
            }
        }
    
        return response()->json($response);
    }  
    public function getqoutationlink(Request $request)
    {
        $callId = $request->input('callId');
        $response = Quotation::where('calls_id', $callId)->pluck('signature_link');    
        return response()->json($response);
    }  
    public function showBySignature($uniqueNumber, $quotationId)
    {
        $quotation = Quotation::find($quotationId);
        if($quotation->signature_status === "Signed")
        {
        
        }
        else{
            $pdfPath = asset('storage/' . $quotation->file_path);
            $logo = asset("images/proforma/milele_logo.png");
                return view('quotation.showsignpage', ['quotation' => $quotation, 'pdfPath' => $pdfPath, 'logo' => $logo, 'filepath' => $quotation->file_path, 'qoutation_id' => $quotation->id]);
        }
            }
    public function submitSignature(Request $request)
    {
        $pdfPath = $request->input('pdf_path');
        $quotationId = $request->input('qoutation_id');
        $signatureData = $request->input('signature_data');
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData)); // Remove data URL prefix
        $pngImagePath = public_path('storage/quotation_files/signatures/') . uniqid() . '.png';
        $directory = public_path('storage/quotation_files/signatures/');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($pngImagePath, $decodedImage);
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile(public_path('storage/quotation_files/' . basename($pdfPath)));
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $pdf->AddPage();
            $templateId = $pdf->importPage($pageNumber);
            $pdf->useTemplate($templateId);
            $text = $this->extractTextFromPage($pdfPath, $pageNumber);
            $signatureY = $this->calculateSignaturePosition($pdf, $pageNumber, $text);
            $x = 100;
            $pdf->Image($pngImagePath, $x, $signatureY, 50, 20);
        }
        $originalFileName = basename($pdfPath);
        $newFileName = str_replace('.pdf', '_signed.pdf', $originalFileName);
        $outputPath = public_path('storage/quotation_files/' . $newFileName);
        $pdf->Output($outputPath, 'F');
    
        unlink($pngImagePath);
    
        // Update the database record
        $quotation = Quotation::find($quotationId);
        $quotation->signature_status = "Signed";
        $quotation->signature_link = null;
        $quotation->file_path = 'quotation_files/' . $newFileName; // Update file_path in the database
        $quotation->save();
        return redirect()->back()->with('success', 'Thank you! Your signature has been successfully submitted.');
    }
private function calculateSignaturePosition($pdf, $pageNumber, $text)
{
    $pageHeight = $pdf->getPageHeight();
    $signatureY = $pageHeight - 30;
    return $signatureY;
}

private function extractTextFromPage($pdfPath, $pageNumber)
{
    $parser = new Parser();
    $pdf = $parser->parseFile(public_path('storage/quotation_files/' . basename($pdfPath)));
    $pages = $pdf->getPages();
    if (isset($pages[$pageNumber])) {
        $text = $pages[$pageNumber]->getText();
        return $text;
    } else {
        return 'Page not found';
    }
}
public function getVehiclesvins(Request $request)
{
    $RowId = $request->input('RowId');
    $quotationItem = QuotationItem::where('uuid', $RowId)->first();

    switch ($quotationItem->reference_type) {
        case 'App\Models\Varaint':
            $vehicles = Vehicles::where('varaints_id', $quotationItem->reference_id)
                   ->where(function ($query) {
                       $query->whereNull('reservation_end_date')
                             ->orWhere('reservation_end_date', '<', Carbon::now());
                   })
                   ->whereNull('so_id')
                   ->get();
            break;
        case 'App\Models\MasterModelLines':
            $variants = Varaint::where('id', $quotationItem->reference_id)->get();
            $vehicles = Vehicles::whereIn('varaints_id', $variants->pluck('id'))->where(function ($query) {
                $query->whereNull('reservation_end_date')
                      ->orWhere('reservation_end_date', '<', Carbon::now());
            })
            ->whereNull('so_id')
            ->get();
            break;
        case 'App\Models\Brand':
            $masterModelLines = MasterModelLines::where('id', $quotationItem->reference_id)->get();
            $variants = Varaint::whereIn('master_model_lines_id', $masterModelLines->pluck('id'))->get();
            $vehicles = Vehicles::whereIn('varaints_id', $variants->pluck('id'))->where(function ($query) {
                $query->whereNull('reservation_end_date')
                      ->orWhere('reservation_end_date', '<', Carbon::now());
            })
            ->whereNull('so_id')
            ->get();
            break;
        default:
            $vehicles = [];
            break;
    }
    return response()->json(['vehicles' => $vehicles]);
}
public function directquotationtocustomer($id)
{
    $useractivities =  New UserActivities();
    $useractivities->activity = "Store the New Direct Lead Automatic";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $client = Clients::find($id);
    $date = Carbon::now();
    $date->setTimezone('Asia/Dubai');
    $dataValue = LeadSource::where('source_name', $client->source)->value('id');
    $formattedDate = $date->format('Y-m-d H:i:s');
    $data = [
            'name' => $client->name,
            'source' => $dataValue,
            'email' => $client->email,
            'sales_person' => Auth::id(),
            'location' => $client->destination,
            'phone' => $client->phone,
            'language' => $client->lauguage,
            'created_at' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "Customer",
            'priority' => "High",
            'customer_coming_type' => "Direct From Sales",
        ];
        $calls = new Calls($data);
        $calls->save();
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $calls->id; 
        $clientleads->clients_id = $client->id;
        $clientleads->save();
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->where('sales_person', Auth::id())
                   ->first();
        $table_id = $lastRecord->id;
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $model = new Logs($logdata);
        $model->save();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Create Quotation";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return redirect()->route('qoutation.proforma_invoice', ['callId' => $calls->id]);
}
public function getVehiclesvinsfirst(Request $request)
{
    $modallineidad = $request->input('modallineidad');
    $variants = Varaint::where('master_model_lines_id', $modallineidad)->get();
    $vehicles = Vehicles::whereIn('varaints_id', $variants->pluck('id'))->where(function ($query) {
        $query->whereNull('reservation_end_date')
              ->orWhere('reservation_end_date', '<', Carbon::now());
    })
    ->whereNull('so_id')
    ->get();
    return response()->json(['vehicles' => $vehicles]);
}
// FileUploadController.php
public function uploadingQuotation(Request $request)
{
    $request->validate([
        'quotationFile' => 'required|file',
        'callId' => 'required'
    ]);

    $file = $request->file('quotationFile');
    $callId = $request->input('callId');

    // Fetching the Quotation based on call ID
    $quotation = Quotation::where('calls_id', $callId)->first();

    if (!$quotation) {
        return response()->json(['error' => 'Quotation not found'], 404);
    }

    $filename = 'Quotation_' . $quotation->id . '_' . date('Y_m_d') . '.pdf';
    $directory = public_path('storage/quotation_files');

    // Ensure the directory exists
    if (!File::isDirectory($directory)) {
        File::makeDirectory($directory, 0777, true, true);
    }

    // Move the uploaded file to the desired location with the new filename
    $file->move($directory, $filename);

    // Update the file path in the database
    $quotation->file_path = 'quotation_files/' . $filename;
    $quotation->signature_status = "Signed";
    $quotation->save();

    return response()->json(['success' => 'File has been uploaded and saved successfully.']);
}
public function getAgentsByQuotationId($quotationId)
    {
        $quotationdetails = QuotationDetail::where('quotation_id' , $quotationId)->first();
        $agents = MuitlpleAgents::with('agent')->where('quotations_id', $quotationId)->where('agents_id', '!=', $quotationdetails->agents_id)->get();
        return response()->json($agents);
    }
}
