<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Customer;
use App\Models\Clients;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\LoiCountryCriteria;
use App\Models\MasterModelLines;
use App\Models\MasterModel;
use App\Models\CountryTTCApprovalModel;
use App\Models\LoiAllowedOrRestrictedModelLines;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoiCountryCriteriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open LOI Restricted Counties List');

        $loiCountryCriterias = LoiCountryCriteria::orderBy('updated_at','DESC')->get();
        foreach($loiCountryCriterias as $loiCountryCriteria) {
            $country_id = $loiCountryCriteria->country_id;
            $loiCountryCriteria->ttc_models = MasterModel::whereHas('TTCApprovalCountry', function($query)use($country_id){
                $query->where('country_id', $country_id);
            })->pluck('model')->toArray();
        }

        return view('loi-country-criterias.index', compact('loiCountryCriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open LOI Restricted Counties Create Page');

        $LOIRestrictedCountries = LoiCountryCriteria::where('status', LoiCountryCriteria::STATUS_ACTIVE)->pluck('country_id');
        $countries = Country::whereNotIn('id', $LOIRestrictedCountries)->get();
        $modelLines = MasterModelLines::all();
        $models = MasterModel::groupBy('model')->get();

        return view('loi-country-criterias.create', compact('countries','modelLines','models'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        DB::beginTransaction();

        $loiCountryCriteria = new LoiCountryCriteria();
        $loiCountryCriteria->country_id = $request->country_id;
        $loiCountryCriteria->comment = $request->comment;
        $loiCountryCriteria->created_by = Auth::id();
        $loiCountryCriteria->is_loi_restricted = $request->is_loi_restricted ? true : false;
        $loiCountryCriteria->is_only_company_allowed = $request->is_only_company_allowed;
        $loiCountryCriteria->max_qty_per_passport = $request->max_qty_per_passport;
        $loiCountryCriteria->max_qty_for_company = $request->max_qty_for_company;
        $loiCountryCriteria->min_qty_for_company = $request->min_qty_for_company;
        $loiCountryCriteria->status = LoiCountryCriteria::STATUS_ACTIVE;
        $loiCountryCriteria->steering = $request->steering;
        if($request->allowed_master_model_line_ids) {
           
            foreach($request->allowed_master_model_line_ids as $allowedModelLine) {

                $loiModelLine = new LoiAllowedOrRestrictedModelLines();
                $loiModelLine->model_line_id = $allowedModelLine;
                $loiModelLine->country_id = $request->country_id;
                $loiModelLine->is_allowed = true;
                $loiModelLine->save();
            }
        }
        if($request->restricted_master_model_line_ids) {
            foreach($request->restricted_master_model_line_ids as $restrictedModelLine) {

                $loiModelLine = new LoiAllowedOrRestrictedModelLines();
                $loiModelLine->model_line_id = $restrictedModelLine;
                $loiModelLine->country_id = $request->country_id;
                $loiModelLine->is_restricted = true;
                $loiModelLine->save();
            }
        }

        $loiCountryCriteria->save();

        if($request->ttc_approval_models) {
            foreach($request->ttc_approval_models as $value) {
                $TTCAprovalModel = new CountryTTCApprovalModel();
                $TTCAprovalModel->master_model_id = $value;
                $TTCAprovalModel->country_id = $request->country_id;
                $TTCAprovalModel->save();
            }
            
        }
        
        (new UserActivityController)->createActivity('Created Entry in  LOI Restricted Counties.');

        DB::commit();

        return redirect()->route('loi-country-criterias.index')->with('success','LOI Restricted Country Added Successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        (new UserActivityController)->createActivity('Open the Edit page LOI Restricted Counties.');
        $loiCountryCriteria = LoiCountryCriteria::find($id);

        $modelLines = MasterModelLines::all();
        $alreadyAddedIds = LoiCountryCriteria::whereNot('country_id', $loiCountryCriteria->country_id
        )->pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();
        $models = MasterModel::groupBy('model')->get();
        $TTCApprovalModels = CountryTTCApprovalModel::where('country_id', $loiCountryCriteria->country_id)
                                    ->pluck('master_model_id')->toArray();

        return view('loi-country-criterias.edit', compact('countries','loiCountryCriteria','modelLines','models',
                'TTCApprovalModels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        DB::beginTransaction();

        $loiCountryCriteria =  LoiCountryCriteria::find($id);
        $loiCountryCriteria->country_id = $request->country_id;
        $loiCountryCriteria->comment = $request->comment;
        $loiCountryCriteria->updated_by = Auth::id();
        $loiCountryCriteria->is_loi_restricted = $request->is_loi_restricted ? true : false;
        $loiCountryCriteria->is_only_company_allowed = $request->is_only_company_allowed;
        $loiCountryCriteria->max_qty_per_passport = $request->max_qty_per_passport;
        $loiCountryCriteria->max_qty_for_company = $request->max_qty_for_company;
        $loiCountryCriteria->min_qty_for_company = $request->min_qty_for_company;
        $loiCountryCriteria->updated_by = Auth::id();
        $loiCountryCriteria->steering = $request->steering;
        $loiCountryCriteria->save();
        LoiAllowedOrRestrictedModelLines::where('country_id', $request->country_id)->delete();

        if($request->allowed_master_model_line_ids) {
           
            foreach($request->allowed_master_model_line_ids as $allowedModelLine) {

                $loiModelLine = new LoiAllowedOrRestrictedModelLines();
                $loiModelLine->model_line_id = $allowedModelLine;
                $loiModelLine->country_id = $request->country_id;
                $loiModelLine->is_allowed = true;
                $loiModelLine->save();
            }
        }
        
        if($request->restricted_master_model_line_ids) {
            foreach($request->restricted_master_model_line_ids as $restrictedModelLine) {

                $loiModelLine = new LoiAllowedOrRestrictedModelLines();
                $loiModelLine->model_line_id = $restrictedModelLine;
                $loiModelLine->country_id = $request->country_id;
                $loiModelLine->is_restricted = true;
                $loiModelLine->save();
            }
        }
        $TTCApprovalModels = CountryTTCApprovalModel::where('country_id', $loiCountryCriteria->country_id)->delete();
        if($request->ttc_approval_models) {
         
            foreach($request->ttc_approval_models as $value) {
                $TTCAprovalModel = new CountryTTCApprovalModel();
                $TTCAprovalModel->master_model_id = $value;
                $TTCAprovalModel->country_id = $request->country_id;
                $TTCAprovalModel->save();

            }
        }
        (new UserActivityController)->createActivity('Updated LOI Restricted Counties.');

        DB::commit();

        return redirect()->route('loi-country-criterias.index')->with('success','LOI Country Criteria Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('Deleted Entry in  LOI Restricted Counties.');

        $loiCountryCriteria = LoiCountryCriteria::find($id);
        $loiModeLlines = LoiAllowedOrRestrictedModelLines::where('country_id', $loiCountryCriteria->country_id)->delete();
        $loiCountryCriteria->delete();
        $loiCountryCriteria->deleted_by = Auth::id();
        $loiCountryCriteria->save();

        return response(true);
    }
    public function statusChange(Request $request) {

        (new UserActivityController)->createActivity('Status change don in  LOI restricted counties.');

        $loiCountryCriteria = LoiCountryCriteria::find($request->id);
        $loiCountryCriteria->status = $request->status;
        $loiCountryCriteria->updated_by = Auth::id();
        $loiCountryCriteria->save();

        return response(true);
    }
    public function CheckCountryCriteria(Request $request)
    {
        // $customer = Clients::find($request->country_id);
        $LoiCountryCriteria = LoiCountryCriteria::where('country_id', $request->country_id)->where('status', LoiCountryCriteria::STATUS_ACTIVE)->first();
        $data = [];

        // get the loi count for this customer for thid=s year;
        $year = Carbon::parse($request->loi_date)->format('Y');
        $totalUnitCountUsed = LetterOfIndentItem::with('LOI')
                                    ->whereHas('LOI', function($query) use($year, $request){
                                        $query->where('client_id', $request->customer_id)
                                        ->where('submission_status', LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED)
                                        ->whereYear('date', $year);
                                    })
                                    ->sum('quantity');

        $quantity = $totalUnitCountUsed + $request->total_quantities;

        if(!empty($LoiCountryCriteria->comment)) {
            $data['comment'] = $LoiCountryCriteria->comment;
        }
        $data['error'] = 0;
        if(!empty($LoiCountryCriteria->is_only_company_allowed && $LoiCountryCriteria->is_only_company_allowed == LoiCountryCriteria::YES)) {
          
            if($request->customer_type !== \App\Models\Clients::CUSTOMER_TYPE_COMPANY) {
            
                $data['validation_error'] = 'Only Company Can allow to Create LOI for this Country.';
                $data['validation_error'] = 'Company can Only Create LOI.';
                $data['error'] = 1;
            }
        }
        if($quantity > 0) {
            $msg = "";
            if($totalUnitCountUsed > 0) {
               $msg = 'Already ' .$totalUnitCountUsed.' unit is used by this customer!';    
            }
            if($LoiCountryCriteria->max_qty_per_passport > 0 && $request->customer_type == \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL) {
                if($quantity > $LoiCountryCriteria->max_qty_per_passport) {
                    $data['validation_error'] = 'Total Quantity should be less than allowed quantity( '.$LoiCountryCriteria->max_qty_per_passport.' ). '.$msg;
                    $data['error'] = 1;
                }
            }
            if($LoiCountryCriteria->min_qty_for_company > 0 && $request->customer_type == \App\Models\Clients::CUSTOMER_TYPE_COMPANY) {
                if($LoiCountryCriteria->min_qty_for_company > $quantity) {
                    $data['validation_error'] = 'Total Quantity should be greater than allowed quantity( '.$LoiCountryCriteria->min_qty_for_company.' ). '.$msg;
                    $data['error'] = 1;
                }
            }
            if($LoiCountryCriteria->max_qty_for_company > 0 && $request->customer_type == \App\Models\Clients::CUSTOMER_TYPE_COMPANY) {
                if($LoiCountryCriteria->max_qty_for_company < $quantity) {
                    $data['validation_error'] = 'Total Quantity should be less than allowed quantity( '.$LoiCountryCriteria->max_qty_for_company.' ). '.$msg;
                    $data['error'] = 1;
                }
            }
        }

        if($request->selectedModelLineIds) {
                $LOIRestrictedCountries = MasterModelLines::with('restricredOrAllowedModelLines')
                        ->whereHas('restricredOrAllowedModelLines', function($query) use($request) {
                            $query->where('is_restricted', true)
                            ->where('country_id', $request->country_id);
                        })->pluck('model_line')->toArray();

            $LOIAllowedCountries = MasterModelLines::with('restricredOrAllowedModelLines')
                        ->whereHas('restricredOrAllowedModelLines', function($query) use($request) {
                            $query->where('is_allowed', true)
                            ->where('country_id', $request->country_id);
                        })->pluck('model_line')->toArray();

            $restrictedModelLinesChoosen = [];
            $notAllowedModelLinesChoosen = [];

            foreach($request->selectedModelLineIds as $modelLine) {
                if($LOIRestrictedCountries) {
                    if(in_array($modelLine, $LOIRestrictedCountries)) {
                        
                        $restrictedModelLinesChoosen[] = $modelLine;
                    }
                }
                
                if($LOIAllowedCountries) {
                    if(!in_array($modelLine, $LOIAllowedCountries) ) {
                        $notAllowedModelLinesChoosen[] = $modelLine;
                    }
                }              
            }
         
           
            if($restrictedModelLinesChoosen) {
                $modelLines = array_unique($restrictedModelLinesChoosen);
                $data['validation_error'] = 'Model Line '.implode(", ", $modelLines).' is restricted to this country!';
                $data['error'] = 1;
            }
            if($notAllowedModelLinesChoosen) {
                $modelLines2 = array_unique($notAllowedModelLinesChoosen);
                $data['validation_error'] = 'Model Line '.implode(", ", $modelLines2).' is not allowed to this country!';
                $data['error'] = 1;
            }
        }
        return response()->json($data);

    }
}
