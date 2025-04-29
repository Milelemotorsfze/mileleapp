<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\AddonDetails;
use App\Models\AddonTypes;
use App\Models\Supplier;
use App\Models\SupplierAddons;
use App\Models\MasterModelDescription;
use App\Models\KitItems;
use App\Models\AddonSellingPrice;
use App\Models\PurchasePriceHistory;
use App\Models\SupplierType;
use App\Models\KitCommonItem;
use App\Models\SparePartsNumber;
use App\Models\AddonDescription;
use DB;
use Validator;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\UserActivityController;
use Exception;
class AddonController extends Controller {
    public function index($data) {
        $rowperpage = 12;
        $content = 'addon';
        $addonMasters = AddonDescription::with('Addon')->whereHas('Addon', function($q) use($data) {
            if($data != 'all') {
                $q->where('addon_type',$data);
            }
        })->get();
        $brandMatsers = Brand::select('id','brand_name')->orderBy('brand_name', 'ASC')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $addon1 = AddonDetails::with('AddonDescription');
        if($data != 'all') {
            $addon1 = $addon1->where('addon_type_name',$data);
            if($data == 'P') {
                (new UserActivityController)->createActivity('Open Accessories Listing Section');
            }
            else if($data == 'SP') {
                (new UserActivityController)->createActivity('Open Spare Parts Listing Section');
            }
            else if($data == 'K') {
                (new UserActivityController)->createActivity('Open Kits Listing Section');
            }
        }
        else {
            (new UserActivityController)->createActivity('Open Accessories, Spare Parts and Kit Listing Section');
        }
        $addon1 = $addon1->orderBy('updated_at', 'DESC')->take($rowperpage)->get();
        $addonIds = $addon1->pluck('id');
        $addonIds = json_decode($addonIds);
        foreach($addon1 as $addon) {
            $price = $totalPrice = '';
            if($addon->addon_type_name == 'P' OR $addon->addon_type_name == 'SP') {
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
            else if($addon->addon_type_name == 'K') {
                $supplierAddonDetails = [];
                $supplierAddonDetails = AddonDetails::where('id',$addon->id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.addon.AddonDescription')->first();
                $totalPrice = 0;
                $totalPriceTrue = 'yes';
                foreach($supplierAddonDetails->KitItems as $oneItem) {
                    if($oneItem->kit_item_total_purchase_price != 0) {
                        $totalPrice = $totalPrice + $oneItem->kit_item_total_purchase_price;
                    }
                    else {
                        $totalPriceTrue = 'no';
                    }
                }
                if($totalPriceTrue == 'yes' && $totalPrice != 0) {
                    $addon->LeastPurchasePrices = $totalPrice;
                }
                else {
                    $addon->LeastPurchasePrices = '';
                }
            }
        }
        return view('addon.index',compact('addon1','addonMasters','brandMatsers',
            'modelLineMasters','data','content','rowperpage','addonIds'));
    }
    public function getRelatedModelLines(Request $request) {
        $modelLines = MasterModelLines::select('id','brand_id','model_line');
        if($request->BrandIds != '' && count($request->BrandIds) > 0) {
            $modelLines = $modelLines->whereIn('brand_id',$request->BrandIds);
        }
        $modelLines = $modelLines->get();
        return response()->json($modelLines);
    }
    public function getAddonlists(Request $request) {
        $start = $request->start;
        $rowperpage = 12;
        $content = 'addon';
        $addonIds = $addonsTableData = [];
        $addons = AddonDetails::with('AddonTypes','AddonTypes.modelDescription')->orderBy('updated_at','DESC');
        if($request->AddonIds) {
            $addons = $addons->whereIn('description',$request->AddonIds);
        }
        if($request->BrandIds) {
            if(in_array('yes',$request->BrandIds)) {
                $addons = $addons->where('is_all_brands','yes');
            }
            else {
                $addons = $addons->where(function ($query) use($request) {
                    $query->where('is_all_brands','yes')->orWhere('is_all_brands','no')->whereHas('AddonTypes', function($q) use($request) {
                        $q = $q->whereIn('brand_id',$request->BrandIds);
                        if($request->ModelLineIds) {
                            if(in_array('yes',$request->ModelLineIds)) {
                                $q = $q->orWhere('is_all_model_lines','yes');
                            }
                            else {
                                $q->where( function ($query) use ($request) {
                                    $query = $query->whereIn('model_id',$request->ModelLineIds);
                                });
                            }
                        }
                    });
                });
            }
        }
        elseif($request->ModelLineIds) {
            $addons = $addons->where(function ($query) use($request) {
                $query->where('is_all_brands','yes')->orWhere('is_all_brands','no')->whereHas('AddonTypes', function($q) use($request) {
                    if(!in_array('yes',$request->ModelLineIds)) {
                        $q = $q->whereIn('model_id',$request->ModelLineIds);
                    }
                });
            });
        }
        if($request->addon_type == 'all') {
            $addons = $addons->whereIn('addon_type_name',['P','SP','K']);
        }
        else {
            $addons = $addons->where('addon_type_name',$request->addon_type);
        }
        $fetchedAddonIds = $addons->pluck('id');
        if(count($fetchedAddonIds) > 0 && $request->isAddonBoxView != 1) {
            $addons = AddonDetails::whereIn('id', $fetchedAddonIds)->orderBy('updated_at', 'DESC')->with('AddonTypes', function ($q) use ($request) {
                if ($request->BrandIds) {
                    $q = $q->whereIn('brand_id', $request->BrandIds);
                }
                if ($request->ModelLineIds) {
                    $q = $q->whereIn('model_id', $request->ModelLineIds);
                }
                $q = $q->with('brands', 'modelLines', 'modelDescription')->get();
            })->with('AddonName', 'SellingPrice', 'PendingSellingPrice','AddonDescription');
        }
        $addon1 = $addons->get();
        if($start >= $addons->count()) {
            $addons = [];
            $data['addonIds'] = [];
        }
        else {
            $addons = $addons->skip($start)->take($rowperpage)->get();
            $addonIds = $addons->pluck('id');
            $data['addonIds'] = json_decode($addonIds);
        }
        foreach($addons as $addon) {
            $price = $totalPrice = '';
            if($addon->addon_type_name == 'P' OR $addon->addon_type_name == 'SP') {
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
            else if($addon->addon_type_name == 'K') {
                $supplierAddonDetails = [];
                $supplierAddonDetails = AddonDetails::where('id',$addon->id)->with('AddonName','AddonTypes.brands','SellingPrice','KitItems.addon.AddonDescription')->first();
                $totalPrice = 0;
                $totalPriceTrue = 'yes';
                foreach($supplierAddonDetails->KitItems as $oneItem) {
                    if($oneItem->kit_item_total_purchase_price != 0) {
                        $totalPrice = $totalPrice + $oneItem->kit_item_total_purchase_price;
                    }
                    else {
                        $totalPriceTrue = 'no';
                    }
                }
                if($totalPriceTrue == 'yes' && $totalPrice != 0) {
                    $addon->LeastPurchasePrices = $totalPrice;
                }
                else {
                    $addon->LeastPurchasePrices = '';
                }
            }
        }
        $html = "";
        $i = $request->serial_number;
        if(count($addon1) > 0) {
            foreach($addons as $value => $addon) {
            if($request->isAddonBoxView == 1) {
                $html.= '<input type="hidden" id="addon-type-count-'.$addon->id.'" value="'.$addon->AddonTypes->count().'">
                        <div id="'.$addon->id.'" class="each-addon col-xxl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="row">';
                $html.=         '<div class="col-xxl-7 col-lg-7 col-md-12 col-sm-12 col-12">
                                    <div class="row" style="padding-right:3px; padding-left:3px;">
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Addon Name</div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">';
                if($addon->AddonName->name != '') {
                    $html .= $addon->AddonName->name;
                    if(isset($addon->AddonDescription)){
                        if($addon->AddonDescription->description != '') {
                            $html.=         ' - '.$addon->AddonDescription->description;
                        }
                    }
                }
                $html.=                 '</div>
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Addon Code</div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'. $addon->addon_code.'</div>
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Addon Type</div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">';
                if($addon->addon_type_name == 'K'){
                    $html.=                 'Kit';
                }
                elseif($addon->addon_type_name == 'P') {
                    $html.=                 'Accessories';
                }
                elseif($addon->addon_type_name == 'SP') {
                    $html.=                 'Spare Parts';
                }
                    $html.=             '</div>';
                if($content == '') {
                    if($addon->PurchasePrices->lead_time_min != '' OR $addon->PurchasePrices->lead_time_max != '') {
                        $html.=         '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Lead Time</div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->PurchasePrices->lead_time_min.'';
                        if($addon->PurchasePrices->lead_time_max != '' && $addon->PurchasePrices->lead_time_min < $addon->PurchasePrices->lead_time_max) {
                            $html.=         '- '.$addon->PurchasePrices->lead_time_max.'';
                        }
                        $html.=         '</div>';
                    }
                }
                if($content == '') {
                    if($addon->PurchasePrices!= null) {
                        if($addon->PurchasePrices->purchase_price_aed != '') {
                            if( Auth::user()->hasPermissionTo('supplier-addon-purchase-price-view')) {
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                if ($hasPermission) {
                                    $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Purchase Price</div>
                                            <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->PurchasePrices->purchase_price_aed.' AED</div>';
                                }
                            }
                        }
                    }
                }
                if($content == '') {
                    if($addon->addon_type_name == 'SP') {
                        if($addon->PurchasePrices!= null) {
                            if($addon->PurchasePrices->updated_at != '') {
                                $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Quotation Date</div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->PurchasePrices->updated_at.'</div>';
                            }
                        }
                    }
                }
                if($addon->addon_type_name == 'SP' OR $addon->addon_type_name == 'P') {
                    if(isset($addon->LeastPurchasePrices->purchase_price_aed)) {
                        if( Auth::user()->hasPermissionTo('addon-least-purchase-price-view')) {
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                            if ($hasPermission) {
                                $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Least Purchase Price</div>
                                            <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->LeastPurchasePrices->purchase_price_aed.' AED</div>';
                            }
                        }
                    }
                }
                else if($addon->addon_type_name == 'K') {
                    if($addon->LeastPurchasePrices != '') {
                        if( Auth::user()->hasPermissionTo('addon-least-purchase-price-view')) {
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                            if ($hasPermission) {
                                $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Least Purchase Price</div>
                                            <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->LeastPurchasePrices.' AED</div>';
                            }
                        }
                    }
                }
                if( Auth::user()->hasPermissionTo('addon-selling-price-view')) {
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                    if($hasPermission) {
                        if($addon->SellingPrice!= null OR $addon->PendingSellingPrice!= null) {
                            $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Selling Price</div>
                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">';
                            if($addon->SellingPrice!= null) {
                                if($addon->SellingPrice->selling_price != '') {
                                    $html.= $addon->SellingPrice->selling_price . 'AED';
                                }
                                elseif($addon->PendingSellingPrice!= null){
                                    if($addon->PendingSellingPrice->selling_price != ''){
                                        $html.= $addon->PendingSellingPrice->selling_price .'AED
                                            </br>
                                            <label class="badge badge-soft-danger">Approval Awaiting</label>';
                                    }
                                }
                            }
                            $html.= '</div>';
                        }
                    }
                }
                if($addon->fixing_charges_included) {
                    $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Fixing Charge</div>
                            <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">';
                    if($addon->fixing_charges_included == 'yes') {
                        $html.= '<label class="badge badge-soft-success">Fixing Charge Included</label>';
                    }
                    else {
                        if($addon->fixing_charge_amount != '') {
                            $html.=$addon->fixing_charge_amount .'AED';
                        }
                    }
                    $html.= '</div>';
                }
                if($addon->lead_time) {
                    $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Lead Time</div>
                                <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">';
                    if($content == '') {
                        if($addon->PurchasePrices->lead_time_min != '' OR $addon->PurchasePrices->lead_time_max != '') {
                            $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Lead Time</div>
                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">;'.$addon->PurchasePrices->lead_time_min.'';
                            if($addon->PurchasePrices->lead_time_max != '' && $addon->PurchasePrices->lead_time_min < $addon->PurchasePrices->lead_time_max) {
                                $html.= '-'.$addon->PurchasePrices->lead_time_max.' Days';
                            }
                            $html.= '</div>';
                        }
                    }
                    $html.=     '</div>';
                }
                if($addon->model_year_start OR $addon->model_year_end) {
                    $html.=     '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Model Year</div>
                                <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->model_year_start.'';
                    if($addon->model_year_end != '' && $addon->model_year_start != $addon->model_year_end){
                        $html.= '- '.$addon->model_year_end.'';
                    }
                    $html.=     '</div>';
                }
                if($addon->additional_remarks) {
                    $html.= '<div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">Additional Remarks</div>
                            <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">'.$addon->additional_remarks.'</div>';
                }
                $html.= '</div>
                    </div>
                    <div class="col-xxl-5 col-lg-5 col-md-12 col-sm-12 col-12" style="padding-right:3px; padding-left:3px;">';
                $html.=$this->ImagePage($addon);
                $html.='</div>';
                if($addon->is_all_brands == 'yes') {
                    $html.= '<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6 col-6">Brand</div>
                            <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">All Brands</div>';
                }
                else    {
                    if($addon->addon_type_name == 'SP' OR $addon->addon_type_name == 'K') {
                        $html.= '<div class="labellist labeldesign col-xxl-3 col-lg-3 col-md-3 col-sm-3 col-3"><center>Brand</center></div>
                                <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4 col-sm-4 col-4"><center>Model Line</center></div>
                                <div class="labellist labeldesign col-xxl-5 col-lg-5 col-md-5 col-sm-5 col-5"><center>Model Description</center></div>';
                    }
                    else{
                        $html.= '<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6"><center>Brand</center></div>
                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6"><center>Model Line</center></div>';
                    }
                    foreach($addon->AddonTypes as $key =>$AddonTypes) {
                        $html.= '<div class="divcolorclass" value="5" hidden></div>';
                        if($addon->addon_type_name == 'SP' OR $addon->addon_type_name == 'K') {
                            $html.= '<div class="testtransform divcolor labellist databack1 addon-'.$addon->id.'-brand-'.$key.' col-xxl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                                        '.$AddonTypes->brands->brand_name.'
                                    </div>
                                    <div class="testtransform divcolor labellist databack1 addon-'.$addon->id.'-model-line-'.$key.' col-xxl-4 col-lg-4 col-md-4 col-sm-4 col-4">';
                            if(isset($AddonTypes->modelLines->model_line)) {
                                $html .= $AddonTypes->modelLines->model_line;
                            }
                            if($AddonTypes->is_all_model_lines == 'yes') {
                                $html .= ' All Model Lines';
                            }
  
                            $html .= '</div>
                                    <div class="testtransform divcolor labellist databack1 addon-'.$addon->id.'-model-number-'.$key.' col-xxl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                        '.$AddonTypes->modelDescription->model_description.'
                                    </div>';
                        }
                        else{
                            $html .= ' <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                                            '.$AddonTypes->brands->brand_name.'
                                                            </div>
                                                            <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">';
                            if(isset($AddonTypes->modelLines->model_line)) {
                                $html .= $AddonTypes->modelLines->model_line;
                            }
                            if($AddonTypes->is_all_model_lines == 'yes') {
                                $html .= 'All Model Lines';
                            }

                            $html .= '</div>';
                        }
                    }

                }
                if($addon->is_all_brands == 'no' && $addon->AddonTypes->count() > 3) {
                    $html .= '<div class="row justify-content-center mt-1">
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <button title="View More Model Descriptions" class="btn btn-sm btn-info view-more text-center"
                                              onclick="viewMore('.$addon->id.')"
                                              id="view-more-'.$addon->id.'"  data-key="'.$value.'" >
                                                View More <i class="fa fa-arrow-down"></i>
                                            </button>
                                            <button title="View More Model Descriptions" hidden class="btn btn-sm btn-info view-less text-center"
                                             id="view-less-'.$addon->id.'" data-key="'.$value.'"  onclick="viewLess('.$addon->id.')">
                                                View Less <i class="fa fa-arrow-up"></i>
                                            </button>
                                        </div>
                                    </div>';
                }

                $html .=         '</div>
                                </br>
                                <div class="row" style="position: absolute; bottom: 3px; right: 5px;">
                                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">';
                $html.=    $this->addsellingprice($addon);
                $html.=    $this->actionPage($addon);
                $html .=                   '</div>
                                    </div>
                                </div>';
                $data['addon_box_html'] = $html;
            }else{
                  if($addon->is_all_brands == 'yes') {
                    $html .= ' <tr data-id="1" class="'.$addon->id.'_allbrands tr each-addon-table-row" id="'.$addon->id.'_allbrands">
                                        <td>'. ++$i. '</td>
                                          <td>';
                                          $html.=    $this->ImageTable($addon);

                                                     $html .='</td>
                                          <td> ';
                                          if($addon->AddonName->name != '') {
                                            $html .= $addon->AddonName->name;
                                            if(isset($addon->AddonDescription)){
                                                if($addon->AddonDescription->description != '') {
                                                    $html.=         ' - '.$addon->AddonDescription->description;
                                                }
                                            }
                                        }
                                          $html .=  '</td>
                                           <td>';
                                                if($addon->addon_type_name == 'K') {
                                                    $html .= '<label class="badge badge-soft-success">Kit</label>';
                                                } elseif($addon->addon_type_name == 'P')
                                                {

                                                    $html .= '<label class="badge badge-soft-primary">Accessories</label>';
                                                }elseif($addon->addon_type_name == 'SP') {
                                                    $html .= ' <label class="badge badge-soft-warning">Spare Parts</label>';
                                                }

                      $html .=              '</td>
                                            <td> '.$addon->addon_code.' </td>
                                             <td>All Brands</td>
                                            <td>All Model Lines</td>
                                            <td></td>';
                                              if($content == '') {
                                                  $html .= '<td>';
                                                  if (isset($addon->LeastPurchasePrices->lead_time_min) || isset($addon->LeastPurchasePrices->lead_time_max)) {
                                                      $html .= '' . $addon->LeastPurchasePrices->lead_time_min . '';
                                                  }
                                                  if ($addon->LeastPurchasePrices->lead_time_max != ''
                                                      && $addon->LeastPurchasePrices->lead_time_min < $addon->LeastPurchasePrices->lead_time_max) {

                                                      $html .= '- ' . $addon->LeastPurchasePrices->lead_time_max . '';

                                                  }
                                                  if ($addon->LeastPurchasePrices->lead_time_min != '' or $addon->LeastPurchasePrices->lead_time_max != '') {
                                                      $html .= 'Days';
                                                  }

                                                  $html .= '</td>';
                                              }
                      $html .=              '<td>
                                            '.$addon->model_year_start.'';
                                                if($addon->model_year_end != '' && $addon->model_year_start != $addon->model_year_end)
                                                {
                                                    $html .= '- '.$addon->model_year_end.'';
                                                }
                      $html .=          '</td>
                                            <td>'.$addon->additional_remarks.'</td>';

                                          if($content == '') {
                                              if( Auth::user()->hasPermissionTo('supplier-addon-purchase-price-view')) {
                                                  if(Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view'])) {
                                                   $html .=   '<td> '.$addon->PurchasePrices->purchase_price_aed.' AED</td>';
                                                  }
                                              }
                                              $html .= '  <td>'.$addon->PurchasePrices->updated_at.'</td>';
                                          }
                                          if($addon->LeastPurchasePrices!= null) {
                                              if(isset($addon->LeastPurchasePrices->purchase_price_aed)) {
                                                  if( Auth::user()->hasPermissionTo('addon-least-purchase-price-view')) {
                                                      if (Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view'])) {
                                                          $html .= ' <td>'.$addon->LeastPurchasePrices->purchase_price_aed.' AED</td>';
                                                      }
                                                  }
                                              }
                                          }

                                          if( Auth::user()->hasPermissionTo('addon-selling-price-view')) {
                                              if(Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view'])) {
                                                  $html .= '<td>';
                                                      if($addon->SellingPrice == '' && $addon->PendingSellingPrice == '') {
                                                          $html .= ' <label class="badge badge-soft-info">Not Added</label>';
                                                      }elseif($addon->SellingPrice!= null OR $addon->PendingSellingPrice!= null) {
                                                          if($addon->SellingPrice!= null) {
                                                              if($addon->SellingPrice->selling_price != '') {
                                                                  $html .= ''.$addon->SellingPrice->selling_price.' AED';
                                                              }
                                                          }elseif($addon->PendingSellingPrice!= null){
                                                              if($addon->PendingSellingPrice->selling_price != '') {
                                                           $html .=    ''.$addon->PendingSellingPrice->selling_price.' AED
                                                                  <label class="badge badge-soft-danger">Approval Awaiting</label>';
                                                              }
                                                          }
                                                      }
                                                      $html .= '</td>';
                                              }
                                          }
                                      if($addon->fixing_charges_included) {
                                          $html .= '<td>';
                                          if($addon->fixing_charges_included == 'yes') {
                                              $html .= ' <label class="badge badge-soft-success">Fixing Charge Included</label>';
                                          }else{
                                              if($addon->fixing_charge_amount != '') {
                                                  $html .= ''.$addon->fixing_charge_amount.' AED';
                                              }
                                          }

                                          $html .= ' </td>';
                                      }


                         $html .=    '<td>';
                                              $html.=    $this->tableAddSellingPrice($addon);
                                              $html.=    $this->actionPage($addon);

                      $html .=              '</td>
                                        </tr>';
                }else{
                      $AddonTypes = AddonTypes::where('addon_details_id', $addon->id)->get();
                      foreach($AddonTypes as $key => $AddonTypes) {

                          $html .= '<tr data-id="1" class="';
                              if($AddonTypes->is_all_model_lines == 'yes') {
                                  $html .= ''.$addon->id.'_'.$AddonTypes->brand_id.'_'.'all_model_lines';
                              }else{
                                  $html .= ''.$addon->id.'_'.$AddonTypes->brand_id.'_'.$AddonTypes->model_id.'';
                              }
                              $html .= ' each-addon-table-row" id="'.$addon->id.'_'.$AddonTypes->brand_id.'">';
                              $html .=  '<td> '. ++$i. '</td>
                                        <td>';
                                        $html.=    $this->ImageTable($addon);
                                                $html .= '</td>
                                        <td>';
                                        if($addon->AddonName->name != '') {
                                            $html .= $addon->AddonName->name;
                                            if(isset($addon->AddonDescription)){
                                                if($addon->AddonDescription->description != '') {
                                                    $html.=         ' - '.$addon->AddonDescription->description;
                                                }
                                            }
                                        }
                                        $html .=  '</td>
                                        <td>';
                                          if($addon->addon_type_name == 'K') {
                                              $html .=   '<label class="badge badge-soft-success">Kit</label>';
                                          }elseif($addon->addon_type_name == 'P') {
                                              $html .=   '  <label class="badge badge-soft-primary">Accessories</label>';
                                          }elseif($addon->addon_type_name == 'SP') {
                                              $html .=   '<label class="badge badge-soft-warning">Spare Parts</label>';
                                          }

                     $html .=           '</td>
                                         <td>'.$addon->addon_code.'</td>
                                          <td>'.$AddonTypes->brands->brand_name.'</td>
                                          <td>';
                                              if($AddonTypes->is_all_model_lines == 'yes') {
                                                 $html .= 'All Model Lines';
                                              }else{
                                                  $html .= ''.$AddonTypes->modelLines->model_line ?? " " .'';
                                              }

                            $html .=     '</td>
                                          <td>';
                                              if($AddonTypes->modelDescription) {
                                                  $html .= ''.$AddonTypes->modelDescription->model_description ?? " ".'';
                                              }
                                              $html .= '</td>';
                                          if($content == '') {
                                              $html .=        '<td>';
                                                  if(isset($addon->LeastPurchasePrices->lead_time_min) || isset($addon->LeastPurchasePrices->lead_time_max)) {
                                                      if($addon->LeastPurchasePrices->lead_time_min != '' OR $addon->LeastPurchasePrices->lead_time_max != '') {
                                                          $html .= ''. $addon->LeastPurchasePrices->lead_time_min .'';
                                                      }
                                                      if($addon->LeastPurchasePrices->lead_time_max != ''
                                                          && $addon->LeastPurchasePrices->lead_time_min < $addon->LeastPurchasePrices->lead_time_max) {
                                                          $html .= '- '.$addon->LeastPurchasePrices->lead_time_max.'';
                                                      }
                                                      $html .= 'Days';
                                                  }
                                              $html .=      '</td>';
                                          }

                            $html .=      '<td>'. $addon->model_year_start .'';
                                                  if($addon->model_year_end != '' && $addon->model_year_start != $addon->model_year_end) {
                                                      $html .= ' -'.$addon->model_year_end.' ';
                                                  }

                          $html .=      ' </td>
                                          	<td>'.$addon->additional_remarks.'</td>';
                                              if($content == '') {
                                                  if(Auth::user()->hasPermissionTo('supplier-addon-purchase-price-view')) {
                                                      if(Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view'])) {
                                                          $html .= '<td>'.$addon->PurchasePrices->purchase_price_aed.' </td>';
                                                      }
                                                  }
                                                  $html .= '<td>'.$addon->PurchasePrices->updated_at.'</td>';
                                              }
                                              if(Auth::user()->hasPermissionTo('addon-least-purchase-price-view')) {
                                                  if(Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view'])) {
                                                      $html .= ' <td>';
                                                      if($addon->LeastPurchasePrices!= null) {
                                                        if($addon->addon_type_name == 'SP' OR $addon->addon_type_name == 'P') {
                                                            if($addon->LeastPurchasePrices->purchase_price_aed != '') {
                                                                $html .= ''.$addon->LeastPurchasePrices->purchase_price_aed.' AED';
                                                            }
                                                        }
                                                        else if($addon->addon_type_name == 'K') {
                                                            if($addon->LeastPurchasePrices != '') {
                                                                $html .= ''.$addon->LeastPurchasePrices.' AED';
                                                            }
                                                        }
                                                      }
                                                      $html .= '</td>';
                                                  }
                                              }
                                              if(Auth::user()->hasPermissionTo('addon-selling-price-view')) {
                                                  if (Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view'])) {
                                                      $html .= '<td>';
                                                      if ($addon->SellingPrice == '' && $addon->PendingSellingPrice == '') {
                                                          $html .= '<label class="badge badge-soft-info">Not Added</label>';
                                                      } elseif ($addon->SellingPrice != null) {
                                                          if ($addon->SellingPrice->selling_price != '') {
                                                              $html .= '' . $addon->SellingPrice->selling_price . ' AED';
                                                          }
                                                      } elseif ($addon->PendingSellingPrice != null) {
                                                          if ($addon->PendingSellingPrice->selling_price != '') {
                                                              $html .= '' . $addon->PendingSellingPrice->selling_price . ' AED
                                                                                <label class="badge badge-soft-danger">Approval Awaiting</label>';
                                                          }
                                                      }

                                                      $html .= '</td>';
                                                  }
                                              }
                                             $html .= '<td>';
                                                      if($addon->fixing_charges_included == 'yes') {
                                                          $html .= '<label class="badge badge-soft-success">Fixing Charge Included</label>';
                                                      }else {
                                                          if($addon->fixing_charge_amount != '') {
                                                              $html .=  ''.$addon->fixing_charge_amount.' AED';
                                                          }
                                                      }
                                          $html .= '</td>
                                                    <td>'.$addon->part_number.'</td>
                                                    <td>';
                          $html.=    $this->modelAddonSellingPrice($addon,$AddonTypes);
                          $html.=    $this->actionPage($addon);
                            $html .= 	'</td>
                          </tr>';

                      }
                  }

                $data['table_html'] = $html;

            }
        }
        $data['serial_number'] = $i;
        }
        else
        {
            if($request->isAddonBoxView == 1)
            {
                $html .='<h6 id="noData" style="text-align:center; padding-top:10px;">No data found !!</h6>';
                $data['addon_box_html'] = $html;
            }
            else
            {
                $html .='<h6 id="noData" style="text-align:center; padding-top:10px;">No data found !!</h6>';
                $data['table_html'] = $html;
            }
            $data['serial_number'] = '';
        }

        if($request->BrandIds) {
            $modelLines = MasterModelLines::select('id', 'brand_id', 'model_line');
            if ($request->BrandIds != '' && count($request->BrandIds) > 0) {
                $modelLines = $modelLines->whereIn('brand_id', $request->BrandIds);
            }
            $modelLines = $modelLines->get();
           $data['model_lines'] = $modelLines;
        }else{
            $modelLines = MasterModelLines::select('id', 'brand_id', 'model_line')->get();
            $data['model_lines'] = $modelLines;
        }
        return response($data);
    }
    function ImageTable($addon) {
        $addonsdata = $addon;
        return view('addon.imageTable', compact('addonsdata'));
    }
    function ImagePage($addon) {
        $addonsdata = $addon;
        return view('addon.imagePage', compact('addonsdata'));
    }
    function actionPage($addon) {
        $addonsdata = $addon;
        return view('addon.action.action', compact('addonsdata'));
    }
    function addsellingprice($addon) {
        $addonsdata = $addon;
        return view('addon.action.addsellingprice', compact('addonsdata'));
    }
    function tableAddSellingPrice($addon) {
        $addonsdata = $addon;
        return view('addon.action.tableAddSellingPrice', compact('addonsdata'));
    }
    function modelAddonSellingPrice($addon, $AddonTypes) {
        $addonsdata = $addon;
        $AddonTypes = $AddonTypes;
        return view('addon.action.modelAddonSellingPrice', compact('addonsdata','AddonTypes'));
    }
    public function create(Request $request) {
        $description_id = $addon_id = $addon_code = $addon_type = $submitFrom =  $addonDescription =  $kitBrand = $kitId = '';
        $kitModelLines = $vendors = [];
        $notAddedModelLines = 0;
        if($request->kit_item_id != '') {
            $kititem = '';
            $kititem = KitCommonItem::where('id',$request->kit_item_id)->first();
            $countBrandModelLines = 0;
            if($kititem != '') {
                $addonTypes = '';
                $addonTypes = AddonTypes::where('addon_details_id',$kititem->addon_details_id)->first();
                if($addonTypes != '') {
                    $kitBrand = $addonTypes->brand_id;
                    $countBrandModelLines = MasterModelLines::where('brand_id',$addonTypes->brand_id)->count();
                }
                $kitModelLines = AddonTypes::where('addon_details_id',$kititem->addon_details_id)
                ->groupBy('addon_details_id','model_id','model_year_start','model_year_end')
                ->select('addon_details_id','model_id','model_year_start','model_year_end')
                ->get();
                $addedModelLines = 0;
                $addedModelLines = AddonTypes::where('addon_details_id',$kititem->addon_details_id)->select('model_id')->distinct('model_id')->count();
                $notAddedModelLines = $countBrandModelLines - $addedModelLines;
                if(count($kitModelLines) > 0) {
                    foreach($kitModelLines as $kitModelLine) {
                        $kitModelLine->allDescriptions = MasterModelDescription::where('model_line_id',$kitModelLine->model_id)->get();
                        $kitModelLine->Descriptions = AddonTypes::where([
                            ['addon_details_id','=',$kitModelLine->addon_details_id],
                            ['model_id','=',$kitModelLine->model_id],
                            ['model_year_start','=',$kitModelLine->model_year_start],
                            ['model_year_end','=',$kitModelLine->model_year_end],
                        ])->pluck('model_number')->toArray();
                    }
                }
                $description_id = $kititem->item_id;
                $addon = '';
                $addon = AddonDescription::where('id',$description_id)->first();
                if($addon != '') {
                    $addon_id = $addon->addon_id;
                    $addonDescription = AddonDescription::where('addon_id',$addon_id)->first();
                }
            }
            $addon_type = 'SP';
            $submitFrom = 'kit';
            $lastAddonCode = AddonDetails::where('addon_type_name','SP')->withTrashed()->orderBy('id', 'desc')->first();
            if($lastAddonCode != ''){
                $lastAddonCodeNo =  $lastAddonCode->addon_code;
                $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                $addon_code =  "SP".$newAddonCodeNumber;
            }
            else {
                $addon_code =  "SP1";
            }
            $spVendorsIds = [];
            $spVendorsIds = SupplierType::where('supplier_type','spare_parts')->pluck('supplier_id');
            $vendors =  Supplier::whereIn('id',$spVendorsIds)->select('id','supplier')->get();
            $kitId = $request->kit_id;
        }
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $addons = Addon::whereIn('addon_type',['P','SP','K','W'])->select('id','name','addon_type')->orderBy('name', 'ASC')->get();
        $brands = Brand::select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $suppliers = Supplier::select('id','supplier')->get();
        (new UserActivityController)->createActivity('Open Create New Addon Form');
        return view('addon.create',compact('addons','brands','modelLines','suppliers','kitItemDropdown','description_id','addon_id','addon_type','addon_code',
                                            'addonDescription','submitFrom','kitBrand','kitModelLines','notAddedModelLines','vendors','kitId'));
    }
    public function store(Request $request) { 
        $authId = Auth::id();
        // $validator = Validator::make($request->all(), [
        //     'addon_type' => 'required',
        //     'addon_code' => 'required',
        //     'addon_id' => 'required',
        //     'image' => 'required|image|mimes:svg,jpeg,png,jpg,gif,bmp,tiff,jpe,jfif',
        // ]);
        // if ($validator->fails()) {
        //     // if($request->addon_type == 'K') {
        //     //     $validator = Validator::make($request->all(), [
        //     //         'brand_id' => 'required',
        //     //         'brandModel' =>'required',
        //     //         'mainItem' => 'required',
        //     //     ]);
        //     // }
        //     // else if($request->addon_type == 'SP') {
        //     //     $validator = Validator::make($request->all(), [
        //     //         'fixing_charges_included' => 'required',
        //     //         'part_number' =>'required',
        //     //         'brand' =>'required',
        //     //     ]);
        //     // }
        //     // else if($request->addon_type == 'P') {
        //     //     $validator = Validator::make($request->all(), [
        //     //         'fixing_charges_included' => 'required',
        //     //     ]);
        //     // }
        //     if($request->addon_type == 'P' OR $request->addon_type == 'SP') {
        //         return redirect(route('addon.create'))->withInput()->withErrors($validator);
        //     }
        //     else if($request->addon_type == 'K') {
        //         return redirect(route('addon.create'))->withInput()->withErrors($validator);
        //     }      
        // }
        // else {
        // try {
        //     DB::beginTransaction();
            $input = $request->all();
            if($request->image) {
                $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();
                $type = $request->image->getClientMimeType();
                $size = $request->image->getSize();
                $request->image->move(public_path('addon_image'), $fileName);
                $input['image'] = $fileName;
            }
            $input['addon_id'] = $request->addon_id;
            $input['currency'] = 'AED';
            $input['created_by'] = $authId;
            $masterAddonByType = Addon::where('addon_type',$request->addon_type)->pluck('id');
            if(count($masterAddonByType) > 0) {
                $lastAddonCode = AddonDetails::where('addon_type_name',$request->addon_type)->whereIn('addon_id',$masterAddonByType)->withTrashed()->orderBy('id', 'desc')->first();
                if($lastAddonCode != '') {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    if($request->addon_type == 'SP') {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else {
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $input['addon_code'] = $request->addon_type.$newAddonCodeNumber;
                }
                else {
                    $input['addon_code'] = $request->addon_type."1";
                }
            }
            else {
                $input['addon_code'] = $request->addon_type."1";
            }
            $input['addon_type_name'] = $request->addon_type;
            $input['addon_id']= $request->addon_id;
            if($request->description != null) {
                $input['description'] = $request->description;
            }
            else {
                if($request->addon_type == 'P' || $request->addon_type == 'SP') {
                    $exisingDescription = AddonDescription::where([
                                                            ['addon_id','=',$request->addon_id],
                                                            ['description','=',$request->description_text]
                    ])->first();
                    if($exisingDescription != '') {
                        $input['description'] = $exisingDescription->id;
                    }
                    else {
                        $createDescription['addon_id'] = $request->addon_id;
                        $createDescription['description'] = $request->description_text;
                        $createdDesc = AddonDescription::create($createDescription);
                        $input['description'] = $createdDesc->id;
                    }
                }
                else if($request->addon_type == 'K') {
                    $kitDescription = AddonDescription::where('addon_id',$request->addon_id)->first();
                    $input['description'] = $kitDescription->id;
                }
            }
            $input['fixing_charge_amount'] = null;
            $addon_details = AddonDetails::create($input);
            if($request->addon_type == 'SP') {
                if(count($request->part_number) > 0) {
                    foreach($request->part_number as $part_number) {
                        $createPartNum = [];
                        $createPartNum['addon_details_id'] = $addon_details->id;
                        $createPartNum['part_number'] = $part_number;
                        $createPartNumber = SparePartsNumber::create($createPartNum);
                    }
                }
            }
            if($request->selling_price != '') {
                $createsellingPriceInput['addon_details_id'] = $addon_details->id;
                $createsellingPriceInput['selling_price'] = $request->selling_price;
                $createsellingPriceInput['status'] = 'pending';
                $createsellingPriceInput['created_by'] = $authId;
                AddonSellingPrice::create($createsellingPriceInput);
            }
            if($request->addon_type == 'SP') {
                if($request->brand) {
                    if(count($request->brand) > 0) {
                        foreach($request->brand as $brandData) {
                            if($brandData['brand_id'] == 'allbrands') {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else {
                                if(isset($brandData['model'])) {
                                    if(count($brandData['model']) > 0) {
                                        foreach($brandData['model'] as $brandModelDta) {
                                            $createAddType = [];
                                            $createAddType['created_by'] = $authId;
                                            $createAddType['addon_details_id'] = $addon_details->id;
                                            $createAddType['brand_id'] = $brandData['brand_id'];
                                            $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                            $createAddType['model_year_end'] = $brandModelDta['model_year_end'];
                                            if($brandModelDta['model_id']) {
                                                if($brandModelDta['model_id'] == 'allmodellines') {
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                                elseif(isset($brandModelDta['model_number'])) {
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    foreach($brandModelDta['model_number'] as $modelDescr) {
                                                        $createAddType['model_number'] = $modelDescr;
                                                        $creBranModelDes = AddonTypes::create($createAddType);
                                                    }
                                                }
                                                else {
                                                    $createAddType['model_id'] = $brandModelDta['model_id'];
                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            elseif ($request->addon_type == 'K') {
                if($request->brand_id) {
                    $brandId = $request->brand_id;
                    $addon_details->is_all_brands = 'no';
                    $addon_details->update();
                    if(isset($request->brandModel)) {
                        if(count( $request->brandModel) > 0) {
                            foreach($request->brandModel as $key => $brandModelData) {
                                foreach ($brandModelData['model_number'] as $modelNumber) {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandId;
                                    $createAddType['model_id'] = $brandModelData['model_line_id'];
                                    $createAddType['is_all_model_lines'] = 'no';
                                    $createAddType['model_number'] = $modelNumber;
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                    else {
                        $createAddType = [];
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        $createAddType['brand_id'] = $brandId;
                        $createAddType['is_all_model_lines'] = 'no';
                        $creBranModelDes = AddonTypes::create($createAddType);
                    }
                }
            }
            else {
                if($request->brandModel) {
                    if(count($request->brandModel) > 0 ) {
                        foreach($request->brandModel as $brandModel) {
                            if($brandModel['brand_id'] == 'allbrands') {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else {
                                if(isset($brandModel['modelline_id'])) {
                                    foreach($brandModel['modelline_id'] as $modelline_id) {
                                        $inputaddontype = [];
                                        $inputaddontype['addon_details_id'] = $addon_details->id;
                                        $inputaddontype['created_by'] = $authId;
                                        $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                        if($modelline_id == 'allmodellines') {
                                            $inputaddontype['is_all_model_lines'] = 'yes';
                                        }
                                        else {
                                            $inputaddontype['model_id'] = $modelline_id;
                                        }
                                        $addon_types = AddonTypes::create($inputaddontype);
                                    }
                                }
                                else {
                                    $inputaddontype = [];
                                    $inputaddontype['addon_details_id'] = $addon_details->id;
                                    $inputaddontype['created_by'] = $authId;
                                    $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K') {
                if($request->mainItem) {
                    if(count($request->mainItem) > 0 ) {
                        foreach($request->mainItem as $kitItemData) {
                            $createkit = [];
                            $createkit['created_by'] = $authId;
                            $createkit['item_id'] = $kitItemData['item'];
                            $createkit['addon_details_id'] = $addon_details->id;
                            $createkit['quantity'] = $kitItemData['quantity'];
                            $CreateSupAddPri = KitCommonItem::create($createkit);
                        }
                    }
                }
            }
            else {
                if($request->supplierAndPrice) {
                    if(count($request->supplierAndPrice) > 0) {
                        foreach($request->supplierAndPrice as $supplierAndPrice1) {
                            $supPriInput['addon_details_id'] = $addon_details->id;
                            $supPriInput['purchase_price_aed'] = $supplierAndPrice1['addon_purchase_price_in_aed'];
                            $supPriInput['purchase_price_usd'] = $supplierAndPrice1['addon_purchase_price_in_usd'];
                            $supPriInput['created_by'] = $authId;
                            if($supplierAndPrice1['supplier_id']) {
                                if(count($supplierAndPrice1['supplier_id']) > 0) {
                                    foreach($supplierAndPrice1['supplier_id'] as $suppl1) {
                                        $supPriInput['supplier_id'] = $suppl1;
                                        if($supPriInput['purchase_price_aed'] != '') {
                                            if(isset($supplierAndPrice1['lead_time']) && $supplierAndPrice1['lead_time_max']) {
                                                if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '') {
                                                    if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max'])) {
                                                        $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                        $supPriInput['lead_time_max'] = NULL;
                                                    }
                                                    elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max'])) {
                                                        $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                        $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                                    }
                                                }
                                                elseif($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] == '') {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                                else {
                                                    $supPriInput['lead_time_min'] = NULL;
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                            }
                                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                            $createHistrory = PurchasePriceHistory::create($supPriInput);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($request->is_from == 'kit') {
                (new UserActivityController)->createActivity('New Kit Created');
                return redirect()->route('kit.kitItems', $request->kit_id)
                ->with('success','Kit created successfully');
            }
            else {
                if($request->addon_type == 'K') {
                    (new UserActivityController)->createActivity('New Kit Created');
                    return redirect()->route('kit.kitItems', $addon_details->id)
                                    ->with('success','Kit created successfully');
                }
                else {
                    if($request->addon_type == 'P') {
                        $typename = 'Accessories';
                    }
                    else if($request->addon_type == 'SP') {
                        $typename = 'Spare Parts';
                    }
                    (new UserActivityController)->createActivity('New' .$typename. ' Created');
                    return redirect()->route('addon.list', $request->addon_type)
                                    ->with('success',$typename.' created successfully');
                }
            }
        //     DB::commit();  
        // } 
        // catch (Exception $e) {
        //     DB::rollback();
        //     info($e);
        //     // throw $e;
        //     abort(500); 
        // }
        // }
    }
    public function destroy(string $id) {
        $addonDetails = AddonDetails::findOrFail($id);
        DB::beginTransaction();
            AddonTypes::where('addon_details_id', $id)->delete();
            AddonSellingPrice::where('addon_details_id', $id)->delete();
            $supplierAddons = SupplierAddons::where('addon_details_id', $id)->get();
            foreach($supplierAddons as $supplierAddon) {
                KitItems::where('supplier_addon_id',$supplierAddon->id)->delete();
                PurchasePriceHistory::where('supplier_addon_id', $supplierAddon->id)->delete();
            }
            KitCommonItem::where('addon_details_id',$id)->delete();
            SupplierAddons::where('addon_details_id', $id)->delete();
            $addonDetails->delete();
            (new UserActivityController)->createActivity($addonDetails->addon_code. ' Deleted');
        DB::commit();
        return response(true);
    }
    public function editAddonDetails($id) {
        $addonDetails = AddonDetails::where('id',$id)->with('partNumbers','AddonTypes','AddonName','AddonSuppliers','SellingPrice','PendingSellingPrice')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$addonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $addonDetails->LeastPurchasePrices = $price;
        $addons = Addon::where('addon_type',$addonDetails->addon_type_name)->select('id','name','addon_type')->get();
        $existingBrandId = [];
        $existingBrandModel = [];
        if($addonDetails->is_all_brands == 'no') {
            $existingBrandModel = AddonTypes::where('addon_details_id',$id)->groupBy('brand_id')->with('brands')->get();
            foreach($existingBrandModel as $data) {
                array_push($existingBrandId,$data->brand_id);
                $jsonmodelLine = [];
                $data->ModelLine = AddonTypes::where([
                    ['addon_details_id','=',$id],
                    ['brand_id','=',$data->brand_id]
                    ])->groupBy('model_id')->with('modelLines')->get();
                    $data->ModelLine->modeldes = [];
                if($data->is_all_model_lines == 'no') {
                    foreach($data->ModelLine as $mo) {
                        $mo->allDes = MasterModelDescription::where('model_line_id',$mo->model_id)->get();
                        $mo->modeldes = AddonTypes::where([
                            ['addon_details_id','=',$id],
                            ['brand_id','=',$mo->brand_id],
                            ['model_id','=',$mo->model_id],
                            ])->pluck('model_number');
                            $mo->modeldes = json_decode($mo->modeldes);
                    }
                }
                $modelLinesData = AddonTypes::where([
                                                    ['addon_details_id','=',$id],
                                                    ['brand_id','=',$data->brand_id]
                                                    ])->pluck('model_id');
                $jsonmodelLine = json_decode($modelLinesData);
                $data->modelLinesData = $jsonmodelLine;
                $data->ModalLines = MasterModelLines::where('brand_id',$data->brand_id)->get();
            }
        }
        $brands = Brand::whereNotIn('id',$existingBrandId)->select('id','brand_name')->get();
        $modelLines = MasterModelLines::select('id','brand_id','model_line')->get();
        $typeSuppliers = SupplierType::select('supplier_id','supplier_type');
        if($addonDetails->addon_type_name == 'P') {
            $typeSuppliers = $typeSuppliers->where('supplier_type','accessories');
        }
        elseif($addonDetails->addon_type_name == 'SP') {
            $typeSuppliers = $typeSuppliers->where('supplier_type','spare_parts');
        }
        elseif($addonDetails->addon_type_name == 'K') {
            $typeSuppliers = $typeSuppliers->whereIn('supplier_type',['accessories','spare_parts']);
        }
        $typeSuppliers = $typeSuppliers->pluck('supplier_id');
        $existingSupplierId = SupplierAddons::where([
                                                ['addon_details_id', '=', $addonDetails->id],
                                                ['status', '=', 'active'],
                                            ])->pluck('supplier_id');
        $suppliers = Supplier::whereNotIn('id',$existingSupplierId)->whereIn('id',$typeSuppliers)->select('id','supplier')->get();
        $kitItemDropdown = Addon::whereIn('addon_type',['P','SP'])->pluck('id');
        $kitItemDropdown = AddonDetails::whereIn('addon_id', $kitItemDropdown)->with('AddonName')->get();
        $supplierAddons = SupplierAddons::where([
                                            ['addon_details_id', '=', $addonDetails->id],
                                            ['status', '=', 'active'],
                                        ])->groupBy(['purchase_price_aed','purchase_price_usd','lead_time_min','lead_time_max'])
                                        ->select('id','purchase_price_aed','purchase_price_usd','addon_details_id','status','lead_time_min','lead_time_max')
                                        ->get();
        foreach($supplierAddons as $supplierAddon) {
            $supplierId = [];
            $supplierId = SupplierAddons::where([
                                            ['addon_details_id','=',$supplierAddon->addon_details_id],
                                            ['purchase_price_aed', '=', $supplierAddon->purchase_price_aed],
                                            ['purchase_price_usd', '=', $supplierAddon->purchase_price_usd],
                                            ['lead_time_min', '=', $supplierAddon->lead_time_min],
                                            ['lead_time_max', '=', $supplierAddon->lead_time_max],
                                        ])->pluck('supplier_id');
            $supplierAddon->suppliers = Supplier::whereIn('id',$supplierId)->select('id','supplier')->get();
        }
        $descriptions = AddonDescription::where('addon_id', $addonDetails->addon_id)->whereNotNull('description')->select('id','description')->get();
        (new UserActivityController)->createActivity('Open '.$addonDetails->addon_code. ' Edit Page');
        return view('addon.edit.edit',compact('addons','brands','modelLines','addonDetails','suppliers',
            'kitItemDropdown','supplierAddons','existingBrandModel','descriptions'));
    }
    public function updateAddonDetails(Request $request, $id) {
        $request->addon_type = $request->addon_type_hiden;
        $authId = Auth::id();
        $addon_details = AddonDetails::find($id);
        if($request->image) {
            $fileName = auth()->id() . '_' . time() . '.'. $request->image->extension();
            $type = $request->image->getClientMimeType();
            $size = $request->image->getSize();
            $request->image->move(public_path('addon_image'), $fileName);
            $addon_details->image = $fileName;
        }
        $addon_details->addon_id = $request->addon_id;
        $addon_details->updated_by = $authId;
        $addon_details->addon_type_name = $request->addon_type_hiden;
        $addon_details->addon_code = $request->addon_code;
        $addon_details->payment_condition = $request->payment_condition;
        $addon_details->additional_remarks = $request->additional_remarks;
        $addon_details->is_all_brands = $request->additional_remarks;
        $addon_details->fixing_charges_included = $request->fixing_charges_included;
        if($request->fixing_charges_included == 'no') {
            $addon_details->fixing_charge_amount = $request->fixing_charge_amount;
        }
        else {
            $addon_details->fixing_charge_amount = NULL;
        }
        if($request->addon_type_hiden == 'SP') {
            $deletePartNumbers = SparePartsNumber::where('addon_details_id',$id)->get();
            if(count($deletePartNumbers) > 0) {
                foreach($deletePartNumbers as $deletePartNumber) {
                    $deletePartNumber->delete();
                }
            }
            if(count($request->part_number) > 0) {
                foreach($request->part_number as $part_number) {
                    $createPartNum = [];
                    $createPartNum['addon_details_id'] = $addon_details->id;
                    $createPartNum['part_number'] = $part_number;
                    $createPartNumber = SparePartsNumber::create($createPartNum);
                }
            }
        }
        else {
            $deletePartNumbers = SparePartsNumber::where('addon_details_id',$id)->get();
            if(count($deletePartNumbers) > 0) {
                foreach($deletePartNumbers as $deletePartNumber) {
                    $deletePartNumber->delete();
                }
            }
        }
        if($request->description != null) {
            $addon_details->description = $request->description;
        }
        else {
            if($request->addon_type_hiden == 'P' || $request->addon_type_hiden == 'SP') {
                $exisingDescription = AddonDescription::where([
                                                        ['addon_id','=',$request->addon_id],
                                                        ['description','=',$request->description_text]
                ])->first();
                if($exisingDescription != '') {
                    $addon_details->description = $exisingDescription->id;
                }
                else {
                    $createDescription['addon_id'] = $request->addon_id;
                    $createDescription['description'] = $request->description_text;
                    $createdDesc = AddonDescription::create($createDescription);
                    $addon_details->description = $createdDesc->id;
                }
            }
            else if($request->addon_type_hiden == 'K') {
                $kitDescription = AddonDescription::where('addon_id',$request->addon_id)->first();
                $addon_details->description = $kitDescription->id; 
            }
        }
        $addon_details->update();
        $deleteAddonTypes = [];
        $deleteAddonTypes = AddonTypes::where('addon_details_id',$id)->get();
        if(count($deleteAddonTypes) > 0) {
            foreach($deleteAddonTypes as $deleteAddonType) {
                $deleteAddonType->deleted_by = Auth::id();
                $deleteAddonType->update();
                $deleteAddonType->delete();
            }
        }
            if($request->addon_type == 'SP') {
                if($request->brand) {
                    if(count($request->brand) > 0) {
                        foreach($request->brand as $brandData) {
                            if($brandData['brand_id'] == 'allbrands') {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else {
                                $addon_details->is_all_brands = 'no';
                                $addon_details->update();
                                if(isset($brandData['model'])) {
                                    if(count($brandData['model']) > 0) {
                                        foreach($brandData['model'] as $brandModelDta) {
                                            if($brandModelDta['model_id']) {
                                                if($brandModelDta['model_id'] == 'allmodellines') {
                                                    $createAddType = [];
                                                    $createAddType['created_by'] = $authId;
                                                    $createAddType['addon_details_id'] = $addon_details->id;
                                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                                    $createAddType['is_all_model_lines'] = 'yes';
                                                    $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                    $createAddType['model_year_end'] = $brandModelDta['model_year_end'];

                                                    $creBranModelDes = AddonTypes::create($createAddType);
                                                }
                                                else {
                                                    if(isset($brandModelDta['model_number'])) {
                                                        foreach($brandModelDta['model_number'] as $modelDescr) {
                                                            $createAddType = [];
                                                            $createAddType['created_by'] = $authId;
                                                            $createAddType['addon_details_id'] = $addon_details->id;
                                                            $createAddType['brand_id'] = $brandData['brand_id'];
                                                            $createAddType['model_id'] = $brandModelDta['model_id'];
                                                            $createAddType['model_number'] = $modelDescr;
                                                            $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                            $createAddType['model_year_end'] = $brandModelDta['model_year_end'];
                                                            $creBranModelDes = AddonTypes::create($createAddType);
                                                        }
                                                    }
                                                    else {
                                                        $createAddType = [];
                                                        $createAddType['created_by'] = $authId;
                                                        $createAddType['addon_details_id'] = $addon_details->id;
                                                        $createAddType['brand_id'] = $brandData['brand_id'];
                                                        $createAddType['model_id'] = $brandModelDta['model_id'];
                                                        $createAddType['model_year_start'] = $brandModelDta['model_year_start'];
                                                        $createAddType['model_year_end'] = $brandModelDta['model_year_end'];
                                                        $creBranModelDes = AddonTypes::create($createAddType);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandData['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                }
            }
            elseif ($request->addon_type == 'K') {
                if($request->brand_id) {
                    $brandId = $request->brand_id;
                    $addon_details->is_all_brands = 'no';
                    $addon_details->update();
                    if(isset($request->brandModel)) {
                        if(count($request->brandModel) > 0) {
                            foreach($request->brandModel as $key => $brandModelData) {
                                foreach ($brandModelData['model_number'] as $modelNumber) {
                                    $createAddType = [];
                                    $createAddType['created_by'] = $authId;
                                    $createAddType['addon_details_id'] = $addon_details->id;
                                    $createAddType['brand_id'] = $brandId;
                                    $createAddType['model_id'] = $brandModelData['model_line_id'];
                                    $createAddType['is_all_model_lines'] = 'no';
                                    $createAddType['model_number'] = $modelNumber;
                                    $creBranModelDes = AddonTypes::create($createAddType);
                                }
                            }
                        }
                    }
                    else {
                        $createAddType = [];
                        $createAddType['created_by'] = $authId;
                        $createAddType['addon_details_id'] = $addon_details->id;
                        $createAddType['brand_id'] = $brandId;
                        $createAddType['is_all_model_lines'] = 'no';
                        $creBranModelDes = AddonTypes::create($createAddType);
                    }
                }
            }
            else {
                if($request->brandModel) {
                    if(count($request->brandModel) > 0 ) {
                        foreach($request->brandModel as $brandModel) {
                            if($brandModel['brand_id'] == 'allbrands') {
                                $addon_details->is_all_brands = 'yes';
                                $addon_details->update();
                            }
                            else {
                                $addon_details->is_all_brands = 'no';
                                $addon_details->update();
                                if(isset($brandModel['modelline_id'])) {
                                    foreach($brandModel['modelline_id'] as $modelline_id) {
                                        $inputaddontype = [];
                                        $inputaddontype['addon_details_id'] = $addon_details->id;
                                        $inputaddontype['created_by'] = $authId;
                                        $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                        if($modelline_id == 'allmodellines') {
                                            $inputaddontype['is_all_model_lines'] = 'yes';
                                        }
                                        else {
                                            $inputaddontype['model_id'] = $modelline_id;
                                        }
                                        $addon_types = AddonTypes::create($inputaddontype);
                                    }
                                }
                                else {
                                    $inputaddontype = [];
                                    $inputaddontype['addon_details_id'] = $addon_details->id;
                                    $inputaddontype['created_by'] = $authId;
                                    $inputaddontype['brand_id'] = $brandModel['brand_id'];
                                    $createAddType['is_all_model_lines'] = 'yes';
                                    $addon_types = AddonTypes::create($inputaddontype);
                                }
                            }
                        }
                    }
                }
            }
            if($request->addon_type == 'K') {
                if($request->mainItem) {
                    if(count($request->mainItem) > 0 ) {
                        $NotNelete = [];
                        $existingItems = [];
                        $existingItems2 = KitCommonItem::where('addon_details_id',$id)->select('item_id')->get();
                        foreach( $existingItems2 as $existingItems1) {
                            array_push($existingItems,$existingItems1->item_id);
                        }
                        $existingItemSuppliers = [];
                        $existingItemSuppliers = SupplierAddons::where('addon_details_id',$id)->select('id','supplier_id')->get();
                        $existingItemSuppliersId = [];
                        $existingItemSuppliersId = SupplierAddons::where('addon_details_id',$id)->pluck('id');
                        foreach($request->mainItem as $kitItemData) {
                            if(in_array($kitItemData['item'], $existingItems)) {
                                $update =  KitCommonItem::where('item_id',$kitItemData['item'])->where('addon_details_id',$id)->first();
                                $update->updated_by = Auth::id();
                                $update->quantity =  $kitItemData['quantity'];
                                $update->update();
                                array_push($NotNelete,$update->id);
                                if(count($existingItemSuppliers) > 0) {
                                    foreach($existingItemSuppliers as $existingItemSupplier) {
                                        $updateSuplierKitQuanty = KitItems::where('addon_details_id',$update->item_id)->where('supplier_addon_id',$existingItemSupplier->id)->first();
                                        if($updateSuplierKitQuanty != '') {
                                            if($updateSuplierKitQuanty->quantity != $update->quantity) {
                                                $updateSuplierKitQuanty->quantity = $update->quantity;
                                                $updateSuplierKitQuanty->total_price_in_aed = $updateSuplierKitQuanty->unit_price_in_aed * $update->quantity;
                                                $updateSuplierKitQuanty->unit_price_in_usd = $updateSuplierKitQuanty->unit_price_in_aed / 3.6725;
                                                $updateSuplierKitQuanty->total_price_in_usd = $updateSuplierKitQuanty->total_price_in_aed / 3.6725;
                                                $updateSuplierKitQuanty->updated_by = Auth::id();
                                                $updateSuplierKitQuanty->update();
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                $createkit = [];
                                $createkit['created_by'] = $authId;
                                $createkit['item_id'] = $kitItemData['item'];
                                $createkit['addon_details_id'] = $addon_details->id;
                                $createkit['quantity'] = $kitItemData['quantity'];
                                $CreateSupAddPri = KitCommonItem::create($createkit);
                                array_push($NotNelete,$CreateSupAddPri->id);
                                if(count($existingItemSuppliers) > 0) {
                                    foreach($existingItemSuppliers as $existingItemSupplier) {
                                        $createKitItemSup = [];
                                        $createKitItemSup['addon_details_id'] = $CreateSupAddPri->item_id;
                                        $createKitItemSup['supplier_addon_id'] = $existingItemSupplier->id;
                                        $createKitItemSup['quantity'] = $CreateSupAddPri->quantity;
                                        $createKitItemSup['created_by '] = Auth::id();
                                        $createSupAddKit = KitItems::create($createKitItemSup);
                                    }
                                }
                            }
                        }
                        $newExiItems2 = [];
                        $newExiItems = KitCommonItem::where('addon_details_id',$id)->pluck('id');
                        foreach($newExiItems as $newExiItems1) {
                            array_push($newExiItems2,$newExiItems1);
                        }
                        $differenceArray = array_diff($newExiItems2, $NotNelete);
                        $delete = KitCommonItem::whereIn('id',$differenceArray)->get();
                        foreach($delete as $del) {
                            $deleteSupKit = KitItems::where('addon_details_id',$del->item_id)->whereIn('supplier_addon_id',$existingItemSuppliersId)->get();
                            foreach($deleteSupKit as $deleteSupKit1) {
                                $deleteSupKit1->delete();
                            }
                            $deletehistory = PurchasePriceHistory::whereIn('supplier_addon_id',$existingItemSuppliersId)->get();
                            foreach($deletehistory as $deletehistory1) {
                                $deletehistory1->delete();
                            }
                            $del = $del->delete();
                        }
                        $supAddIds = [];
                        $supAddIds = SupplierAddons::where('addon_details_id',$id)->pluck('id');
                        if(count($supAddIds) > 0) {
                            foreach($supAddIds as $supAddId) {
                                $aedSum = '';
                                $usdSum = '';
                                $aedSum = KitItems::where('supplier_addon_id',$supAddId)->sum('total_price_in_aed');
                                $usdSum = KitItems::where('supplier_addon_id',$supAddId)->sum('total_price_in_usd');
                                $sup = SupplierAddons::where('id',$supAddId)->first();
                                if($sup->purchase_price_aed != $aedSum) {
                                    $sup->purchase_price_aed = $aedSum;
                                    $sup->purchase_price_usd = $usdSum;
                                    $sup->updated_by = Auth::id();
                                    $sup->save();
                                    $supPriInput = [];
                                    $supPriInput['created_by'] = Auth::id();
                                    $supPriInput['supplier_id'] = $sup->supplier_id;
                                    $supPriInput['addon_details_id'] = $request->kit_addon_id;
                                    $supPriInput['purchase_price_aed'] =  $aedSum;
                                    $supPriInput['purchase_price_usd'] =  $usdSum;
                                    $supPriInput['supplier_addon_id'] = $sup->id;
                                    $createHistrory = PurchasePriceHistory::create($supPriInput);
                                }
                            }
                        }
                    }
                }
            }
            else {
                $NotNelete = [];
                $existingSuppliers = [];
                $existingSuppliers2 = SupplierAddons::where([
                                                        ['addon_details_id','=',$id],
                                                        ['status','=','active'],
                                                    ])->select('supplier_id')->get();
                foreach( $existingSuppliers2 as $existingSuppliers1) {
                    array_push($existingSuppliers,$existingSuppliers1->supplier_id);
                }
                if($request->supplierAndPrice) {
                    if(count($request->supplierAndPrice) > 0) {
                        foreach($request->supplierAndPrice as $supplierAndPrice1) {
                            if($supplierAndPrice1['supplier_id']) {
                                if(count($supplierAndPrice1['supplier_id']) > 0) {
                                    foreach($supplierAndPrice1['supplier_id'] as $suppl1) {
                                        array_push($NotNelete,$suppl1);
                                        if(in_array($suppl1, $existingSuppliers)) {
                                            $update =  SupplierAddons::where('supplier_id',$suppl1)->where('addon_details_id',$id)->first();
                                            $oldPrice = $update->purchase_price_aed;
                                            $oldSellingPrice = $update->purchase_price_usd;
                                            $update->updated_by = Auth::id();
                                            $update->supplier_id = $suppl1;
                                            $update->purchase_price_aed =  $supplierAndPrice1['addon_purchase_price_in_aed'];
                                            $update->purchase_price_usd =  $supplierAndPrice1['addon_purchase_price_in_usd'];
                                            if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '') {
                                                if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max'])) {
                                                    $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                    $update->lead_time_max = NULL;
                                                }
                                                elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max'])) {
                                                    $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                    $update->lead_time_max = $supplierAndPrice1['lead_time_max'];
                                                }
                                            }
                                            else {
                                                $update->lead_time_min = $supplierAndPrice1['lead_time'];
                                                $update->lead_time_max = $supplierAndPrice1['lead_time_max'];
                                            }
                                            $update->update();
                                            if($oldPrice != $update->purchase_price_aed) {
                                                $createNewHistry['purchase_price_aed'] = $update->purchase_price_aed;
                                                $createNewHistry['purchase_price_usd'] = $update->purchase_price_usd;
                                                $createNewHistry['supplier_addon_id'] = $update->id;
                                                $createNewHistry['status'] = 'active';
                                                $createNewHistry['created_by'] = Auth::id();
                                                $createNewHistry33 = PurchasePriceHistory::create($createNewHistry);
                                            }
                                        }
                                        else {
                                            $supPriInput['addon_details_id'] = $addon_details->id;
                                            $supPriInput['purchase_price_aed'] = $supplierAndPrice1['addon_purchase_price_in_aed'];
                                            $supPriInput['purchase_price_usd'] = $supplierAndPrice1['addon_purchase_price_in_usd'];
                                            if($supplierAndPrice1['lead_time'] != '' && $supplierAndPrice1['lead_time_max'] != '') {
                                                if(intval($supplierAndPrice1['lead_time']) == intval($supplierAndPrice1['lead_time_max'])) {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = NULL;
                                                }
                                                elseif(intval($supplierAndPrice1['lead_time']) < intval($supplierAndPrice1['lead_time_max'])) {
                                                    $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                    $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                                }
                                            }
                                            else {
                                                $supPriInput['lead_time_min'] = $supplierAndPrice1['lead_time'];
                                                $supPriInput['lead_time_max'] = $supplierAndPrice1['lead_time_max'];
                                            }
                                            $supPriInput['created_by'] = $authId;
                                            $supPriInput['supplier_id'] = $suppl1;
                                            $CreateSupAddPri = SupplierAddons::create($supPriInput);
                                            $supPriInput['supplier_addon_id'] = $CreateSupAddPri->id;
                                            $createHistrory2 = PurchasePriceHistory::create($supPriInput);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $newExiSuppliers2 = [];
                    $newExiSuppliers = SupplierAddons::where([
                                                        ['addon_details_id','=',$id],
                                                        ['status','=','active'],
                                                    ])->pluck('id');
                    foreach($newExiSuppliers as $newExiSuppliers1) {
                        array_push($newExiSuppliers2,$newExiSuppliers1);
                    }
                    $differenceArray = array_diff($newExiSuppliers2, $NotNelete);
                    $delete = SupplierAddons::whereIn('supplier_id',$differenceArray)
                                            ->where([
                                                ['addon_details_id','=',$id],
                                                ['status','=','active'],
                                            ])->get();
                    foreach($delete as $del) {
                        $deletehistory = PurchasePriceHistory::where('supplier_addon_id',$del->id)->get();
                        foreach($deletehistory as $deletehistory1) {
                            $deletehistory1->delete();
                        }
                        $del = $del->delete();
                    }
                }
            }
            if($request->addon_type == 'K') {
                (new UserActivityController)->createActivity($addon_details->addon_code. ' Updated');
                return redirect()->route('kit.kitItems', $id)
                ->with('success','Kit updated successfully');
            }
            else if( $request->kit_id != '') {
                (new UserActivityController)->createActivity($addon_details->addon_code. ' Updated');
                return redirect()->route('kit.kitItems', $request->kit_id)
                    ->with('success','Kit updated successfully');
            }
            else {
                (new UserActivityController)->createActivity($addon_details->addon_code. ' Updated');
                $data = 'all';
            return redirect()->route('addon.list', $data)
                            ->with('success','Addon updated successfully');
            }
    }
    public function existingImage($id) {
        $data['addon_type'] = Addon::where('id',$id)->select('addon_type')->first();
        if($data['addon_type']->addon_type != '') {
            $addonType = $data['addon_type']->addon_type;
            $masterAddonByType = Addon::where('addon_type',$addonType)->pluck('id');
            if($masterAddonByType != '') {
                $lastAddonCode = AddonDetails::where('addon_type_name',$addonType)->whereIn('addon_id',$masterAddonByType)->withTrashed()
                ->orderBy('id', 'desc')->first();
                if($lastAddonCode != '') {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    if($addonType == 'SP') {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else {
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $data['newAddonCode'] = $addonType.$newAddonCodeNumber;
                }
                else {
                    $data['newAddonCode'] = $addonType."1";
                }
            }
            else {
                $data['newAddonCode'] = $addonType."1";
            }
        }
        else {
            $data['newAddonCode'] = "";
        }
        return response()->json($data);
    }
    public function addonFilters(Request $request) {
        $request['Data'] = 'SP';
        $request['AddonIds1'] = ['329'];
        $request['BrandIds'] = ['25'];
        $addonIds = $addonsTableData = [];
        if($request->Data != 'all') {
            $addonIds = AddonDetails::where('addon_type_name',$request->Data);
        }
        else {
            $addonIds = AddonDetails::whereIn('addon_type_name',['P','SP','K']);
        }
        if($request->AddonIds) {
            $addonIds = $addonIds->whereIn('addon_id',$request->AddonIds);
        }
        if($request->BrandIds) {
            if(in_array('yes',$request->BrandIds)) {
                $addonIds = $addonIds->where('is_all_brands','yes');
            }
            else {
                $addonIds = $addonIds->where(function ($query) use($request) {
                    $query->where('is_all_brands','yes')
                    ->orWhere('is_all_brands','no')->whereHas('AddonTypes', function($q) use($request) {
                        $q = $q->whereIn('brand_id',$request->BrandIds);
                        if($request->ModelLineIds) {
                            if(in_array('yes',$request->ModelLineIds)) {
                                $q = $q->orWhere('is_all_model_lines','yes');
                            }
                            else {
                                $q->where( function ($query) use ($request) {
                                    $query = $query->whereIn('model_id',$request->ModelLineIds);
                                });
                            }
                        }
                    });
                });
            }
        }
        elseif($request->ModelLineIds) {
            $addonIds = $addonIds->where(function ($query) use($request) {
                $query->where('is_all_brands','yes')
                ->orWhere('is_all_brands','no')->whereHas('AddonTypes', function($q) use($request) {
                    if(!in_array('yes',$request->ModelLineIds)) {
                        $q = $q->whereIn('model_id',$request->ModelLineIds);
                    }
                });
            });
        }
        $addonIds = $addonIds->pluck('id');
        $data['addonsBox'] = $addonIds;
        if(count($addonIds) > 0) {
            $addonsTableData = AddonDetails::whereIn('id',$addonIds)->with('AddonTypes', function($q) use($request) {
                if($request->BrandIds) {
                    $q = $q->whereIn('brand_id',$request->BrandIds);
                }
                if($request->ModelLineIds) {
                    $q = $q->whereIn('model_id',$request->ModelLineIds);
                }
                $q = $q->with('brands','modelLines','modelDescription')->get();
            })->with('AddonName','SellingPrice','PendingSellingPrice');
            $addonsTableData = $addonsTableData->orderBy('id', 'DESC')->get();
            foreach($addonsTableData as $addon) {
                $price = '';
                $price = SupplierAddons::where('addon_details_id',$addon->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
                $addon->LeastPurchasePrices = $price;
            }
        }
        $data['addonsTable'] = $addonsTableData;
        return response()->json($data);
    }
    public function createMasterAddon(Request $request) {
        $authId = Auth::id();
        if($request->addon_type == 'K') {
            $validator = Validator::make($request->all(), [
                'kit_year' => 'required',
                'kit_km' => 'required',
            ]);
        }
        else {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return redirect(route('addon.create'))->withInput()->withErrors($validator);
        }
        else {
            $input = $request->all();
            $input['created_by'] = $authId;
            if($request->addon_type == 'K') {
                $input['name'] = 'Kit: '.$request->kit_year.' year | '.$request->kit_km.'KM';
            }
            $isExisting = Addon::where('name', $input['name'])
                ->where('addon_type', $request->addon_type)->first();
            if($isExisting) {
                $addons['error'] =  "This Addon is Already Existing";
            }
            else{
                $addons = Addon::create($input);
                if($request->addon_type == 'K') {
                    $createDescr['addon_id'] = $addons->id;
                    $createDescr['description'] = NULL;
                    $createInput = AddonDescription::create($createDescr);
                }
            }
            return response()->json($addons);
        }
    }
    public function fetchAddonData($id, $quotationId, $VehiclesId) {
        $result = DB::table('addon_types')
                ->join('addon_details', 'addon_types.addon_details_id', '=', 'addon_details.id')
                ->join('addons', 'addon_details.addon_id', '=', 'addons.id')
                ->where('addon_types.model_id', '=', $id)
                ->select('*', 'addon_types.id as idp')
                ->get();
        return view('quotation.addone',compact('result', 'quotationId', 'VehiclesId'));
    }
    public function brandModels(Request $request, $id) {
        $data = MasterModelLines::where('brand_id',$id)->select('id','model_line');
        if($request->filteredArray) {
            if(count($request->filteredArray) > 0) {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function getAddonCodeAndDropdown(Request $request) {
        if($request->addon_type) {
            $masterAddonByType = Addon::where('addon_type',$request->addon_type)->pluck('id');
            if($masterAddonByType != '') {
                $lastAddonCode = AddonDetails::where('addon_type_name',$request->addon_type)->where('addon_type_name',$request->addon_type)->whereIn('addon_id',$masterAddonByType)
                ->withTrashed()->orderBy('id', 'desc')->first();
                if($lastAddonCode != '') {
                    $lastAddonCodeNo =  $lastAddonCode->addon_code;
                    if($request->addon_type == 'SP') {
                        $lastAddonCodeNumber = substr($lastAddonCodeNo, 2, 5);
                    }
                   else {
                    $lastAddonCodeNumber = substr($lastAddonCodeNo, 1, 5);
                   }
                    $newAddonCodeNumber =  $lastAddonCodeNumber+1;
                    $data['newAddonCode'] = $request->addon_type.$newAddonCodeNumber;
                }
                else {
                    $data['newAddonCode'] = $request->addon_type."1";
                }
            }
            else {
                $data['newAddonCode'] = $request->addon_type."1";
            }
            $data['addonMasters'] = Addon::whereIn('id',$masterAddonByType)->select('id','name')->orderBy('name', 'ASC')->get();
            $addonType = $request->addon_type;
            if($addonType == 'P'){
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                    });
            }
            else if($addonType == 'SP') {
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                    });
            }
            else if($addonType == 'K') {
                $data['suppliers'] = Supplier::with('supplierTypes')
                    ->whereHas('supplierTypes', function ($query) {
                        $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                    });
            }
            $data['suppliers'] = $data['suppliers']->get();
        }
        else {
            $data['newAddonCode'] = "";
        }
        return response()->json($data);
    }
    public function getModelDescriptionDropdown(Request $request) {
        $data['model_description'] = MasterModelDescription::whereIn('model_line_id',$request->model_line_id)->select('id','model_description')->get();
        return response()->json($data);
    }
    public function kitItems($id) {
        $supplierAddonDetails = [];
        $supplierAddonDetails = AddonDetails::where('id',$id)->with('partNumbers','AddonName','AddonTypes.brands','SellingPrice','AddonSuppliers.Suppliers',
        'AddonSuppliers.Kit.addon.AddonName')->first();
        $price = '';
        $price = SupplierAddons::where('addon_details_id',$supplierAddonDetails->id)->where('status','active')->orderBy('purchase_price_aed','ASC')->first();
        $supplierAddonDetails->LeastPurchasePrices = $price;
        $previous = $next = '';
        $previous = AddonDetails::where('addon_type_name',$supplierAddonDetails->addon_type_name)->where('id', '<', $id)->max('id');
        $next = AddonDetails::where('addon_type_name',$supplierAddonDetails->addon_type_name)->where('id', '>', $id)->min('id');
        (new UserActivityController)->createActivity('Open '.$supplierAddonDetails->addon_code.' Details');
        return view('addon.kititems',compact('supplierAddonDetails','previous','next'));
    }
    public function statusChange(Request $request) {
        $authId = Auth::id();
        $sellingPrice = AddonSellingPrice::find($request->id);
        if($request->status == 'active') {
            $oldSellingPrice = AddonSellingPrice::where('addon_details_id',$sellingPrice->addon_details_id)->where('status','active')->first();
            if($oldSellingPrice != '') {
                $oldSellingPrice->status = 'inactive';
                $oldSellingPrice->updated_by = $authId;
                $oldSellingPrice->save();
            }
        }
        $sellingPrice->status = $request->status;
        $sellingPrice->status_updated_by = $authId;
        $sellingPrice->save();
        return response($sellingPrice, 200);
    }
    public function UpdateSellingPrice(Request $request, $id) {
        $request->validate([
            'selling_price' => 'required'
        ]);
        $authId = Auth::id();
        $data = AddonSellingPrice::find($id);
        $data->selling_price = $request->selling_price;
        $data->updated_by = $authId;
        $data->save();
        return redirect()->back()->with('success','Addon Selling Price Updated successfully.');
    }
    public function getSupplierForAddon(Request $request) {
        $data = Supplier::select('id','supplier');
        $addonType = $request->addonType;
        if($addonType == 'P'){
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                })->select('id','supplier');
        }
        else if($addonType == 'SP') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                })->select('id','supplier');
        }
        else if($addonType == 'K') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                })->select('id','supplier');
        }
        if($request->filteredArray)
        {
            if(count($request->filteredArray) > 0)
            {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function getSupplierForAddonType(Request $request) {
        $addonType = $request->addonType;
        if($addonType == 'P'){
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_ACCESSORIES);
                });
        }
        else if($addonType == 'SP') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->where('supplier_type', Supplier::SUPPLIER_TYPE_SPARE_PARTS);
                });
        }
        else if($addonType == 'K') {
            $data = Supplier::with('supplierTypes')
                ->whereHas('supplierTypes', function ($query) {
                    $query->whereIn('supplier_type', [Supplier::SUPPLIER_TYPE_SPARE_PARTS, Supplier::SUPPLIER_TYPE_ACCESSORIES]);
                });
        }
        $data = $data->get();
        return $data;
    }
    public function createSellingPrice(Request $request, $id) {
        $this->validate($request, [
            'selling_price' => 'required',
        ]);
        $authId = Auth::id();
        $input['selling_price'] = $request->selling_price;
        $input['addon_details_id'] = $id;
        $input['created_by'] = $authId;
        $input['status'] = 'pending';
        $createSellingPrice = AddonSellingPrice::create($input);
        $data = 'all';
        return redirect()->route('addon.list', $data)
                        ->with('success','Addon created successfully');
    }
    public function getKitItemsForAddon(Request $request) {
        $kitItemDropdown = Addon::whereIn('addon_type',['SP'])->pluck('id');
        $data = AddonDetails::select('id','addon_code','addon_id','description')
                ->whereIn('addon_id', $kitItemDropdown)->with('AddonName');
        if($request->filteredArray) {
            if(count($request->filteredArray) > 0) {
                $data = $data->whereNotIn('id', $request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function addonStatusChange(Request $request) {
        $addon = AddonDetails::find($request->id);
        $addon->status = $request->status;
        $addon->save();
        return response($addon, 200);
    }
    public function getModelLinesForAddons(Request $request) {
        $data = MasterModelLines::select('id','model_line');
        if($request->filteredArray) {
            if(count($request->filteredArray) > 0) {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        if($request->id) {
            $id = $request->id;
            $alreadyAddedModelLines = AddonTypes::where('brand_id',$id)->pluck('model_id');
            $data = $data->whereNotIn('id', $alreadyAddedModelLines);
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function getBrandForAddons(Request $request) {
        $data = Brand::select('id','brand_name');
        if($request->filteredArray) {
            if(count($request->filteredArray) > 0) {
                $data = $data->whereNotIn('id',$request->filteredArray);
            }
        }
        $data = $data->get();
        return response()->json($data);
    }
    public function getAddonDescription(Request $request) {
        $descriptions = AddonDescription::where('addon_id', $request->addon_id)
                                        ->whereNotNull('description')->select('id','description')
                                        ->get();
        return response($descriptions);
    }
    public function getUniqueAccessories(Request $request) {
        $description = null;
        if($request->description != null) {
            $description = $request->description;
        }
        elseif ($request->newDescription != null) {
            $description = $request->newDescription;
        }
        if($description == null) {
            $descriptionData = AddonDescription::where('addon_id',$request->addon_id)->where('description',NULL)->first();
            if($descriptionData) {
                $description = $descriptionData->id;
            }
        }
        if($request->brand == 'allbrands') {
            $isExisting = AddonDetails::where('is_all_brands', 'yes')
                ->where('addon_id', $request->addon_id)
                ->where('description', $description)
                ->where('addon_type_name', $request->addonType);
                if($request->id) {
                    $isExisting = $isExisting->whereNot('id',$request->id);
                }
           $data['is_all_brands'] = $isExisting->count();
        }
        else {
            $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)
                                    ->where('description', $description)
                                    ->where('addon_type_name', $request->addonType)
                                    ->where('is_all_brands', 'no');
            if($request->id) {
                $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
            }
            $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');
            $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)
                                ->where('brand_id', $request->brand);
            if($isExisting) {
                if($request->model_line[0] == 'allmodellines') {
                    $isExisting = $isExisting->where('is_all_model_lines','yes');
                    $data['model_line'] = 'allmodellines';
                }
                else{
                    $modelLineArray = [];
                    if($request->model_line != null) {
                        $modelLineArray = $request->model_line;
                    }
                    $isExisting = $isExisting->whereIn('model_id',$modelLineArray);
                    if($isExisting && $request->addonType == 'P') {
                        $modelLines = $isExisting->get();
                        $models = [];
                        foreach ($modelLines as $modelLine) {
                            $models[] = $modelLine->modelLines->model_line ?? '';
                        }
                        $data['model_line'] = implode(",", $models);
                    }
                }
            }
        }
        if($isExisting) {
            $data['count'] = $isExisting->count();
        }
        else{
            $data['count'] = 0;
        }
        $data['index'] = $request->index;
        return response($data);
    }
    public function getUniqueSpareParts(Request $request) {
        $description = null;
        if($request->description != null) {
            $description = $request->description;
        }elseif ($request->newDescription != null) {
            $description = $request->newDescription;
        }
        if($description == null) {
            $descriptionData = AddonDescription::where('addon_id',$request->addon_id)->where('description',NULL)->first();
            if($descriptionData) {
                $description = $descriptionData->id;
            }
        }
        $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)->where('description', $description)->where('addon_type_name', $request->addonType);
        if($request->id) {
            $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
        }
        if($request->part_number && $request->part_number != '') {
            $existingAddonDetailIds = $existingAddonDetailIds->whereHas('partNumbers', function($q) use($request) {
                $q = $q->where('part_number',$request->part_number);
            });
        }
        $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');
        $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)->where('brand_id', $request->brand);
        if($isExisting) {
            $isExisting = $isExisting->where('model_id', $request->model_line);
            if($isExisting ) {
                $modelNumber = [];
                if($request->model_number != null) {
                    $modelNumber = $request->model_number;
                }
                $isExisting = $isExisting->whereIn('model_number', $modelNumber);
                $modelNumbers = $isExisting->get();
                $models = [];
                foreach ($modelNumbers as $modelNumber) {
                    $models[] = $modelNumber->modelDescription->model_description ?? '';
                }
                $data['model_number'] = implode(",", $models);
            }
        }
        if($isExisting) {
            $data['count'] = $isExisting->count();
        }
        else{
            $data['count'] = 0;
        }
        $data['i'] = $request->i;
        $data['j'] = $request->j;
        return response($data);
    }
    public function getUniqueKits(Request $request) {
        $existingAddonDetailIds = AddonDetails::where('addon_id', $request->addon_id)->where('addon_type_name', $request->addonType);
        if($request->id) {
            $existingAddonDetailIds = $existingAddonDetailIds->whereNot('id',$request->id);
        }
        $existingAddonDetailIds = $existingAddonDetailIds->pluck('id');
        $isExisting = AddonTypes::whereIn('addon_details_id', $existingAddonDetailIds)->where('brand_id', $request->brand);
        if($isExisting) {
            $isExisting = $isExisting->where('model_id', $request->model_line);
            if($isExisting ) {
                $modelNumber = [];
                if($request->model_number != null) {
                    $modelNumber = $request->model_number;
                }
                $isExisting = $isExisting->whereIn('model_number', $modelNumber);
                $modelNumbers = $isExisting->get();
                $models = [];
                foreach ($modelNumbers as $modelNumber) {
                    $models[] = $modelNumber->modelDescription->model_description ?? '';
                }
                $data['model_number'] = implode(",", $models);
            }
        }
        if($isExisting) {

            $data['count'] = $isExisting->count();
        }
        else{
            $data['count'] = 0;
        }
        $data['index'] = $request->index;
        return response($data);
    }
    public function getUniqueAddonDescription(Request $request) {
        if($request->description) {
            $isExist = AddonDetails::where('addon_type_name', $request->addonType)
                ->where('addon_id', $request->addon_id)
                ->where('description', $request->description)
                ->count();
        }
        else{
            $isExist = 0;
        }
        return response($isExist);
    }
}

