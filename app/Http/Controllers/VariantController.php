<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\ColorCode;
use App\Models\Varaint;
use App\Models\Vehicles;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Variantlog;
use App\Models\ModelSpecification;
use App\Models\ModelSpecificationOption;
use App\Models\VariantItems;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open Variant Information Page');
        $variants = Varaint::orderBy('id','DESC')->get();
        return view('variants.list', compact('variants'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('|Open Create New Variant Page');
        $brands = Brand::all();
        $countries = CountryListFacade::getList('en');
        $masterModelLines = MasterModelLines::all();
        $int_colour = ColorCode::where('belong_to', "int")->get();
        $ex_colour = ColorCode::where('belong_to', "ex")->get();
        return view('variants.create', compact('masterModelLines','brands','int_colour','ex_colour', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $selectedSpecifications = json_decode(request('selected_specifications'), true);
    ksort($selectedSpecifications);
    
    $totalSpecifications = count($selectedSpecifications);
    $existingVariantop = Varaint::where('brands_id', $request->input('brands_id'))
        ->where('master_model_lines_id', $request->input('master_model_lines_id'))
        ->where('fuel_type', $request->input('fuel_type'))
        ->where('engine', $request->input('engine'))
        ->where('coo', $request->input('coo'))
        ->where('my', $request->input('my'))
        ->where('drive_train', $request->input('drive_train'))
        ->where('gearbox', $request->input('gearbox'))
        ->where('steering', $request->input('steering'))
        ->where('upholestry', $request->input('upholestry'))
        ->whereHas('variantItems', function ($q) use ($selectedSpecifications) {
            $q->whereIn('model_specification_id', array_column($selectedSpecifications, 'specification_id'))
              ->whereIn('model_specification_options_id', array_column($selectedSpecifications, 'value'));
        })
        ->orderBy('created_at', 'desc')
        ->first();
    if ($existingVariantop) {
        // Check if all specifications and values match
        $matchedSpecifications = 0;
        foreach ($selectedSpecifications as $specificationData) {
            $matchFound = $existingVariantop->variantItems->contains(function ($variantItem) use ($specificationData) {
                return $variantItem->model_specification_id == $specificationData['specification_id'] &&
                       $variantItem->model_specification_options_id == $specificationData['value'];
            });
    
            if ($matchFound) {
                $matchedSpecifications++;
            }
        }
        if ($matchedSpecifications == $totalSpecifications) {
            return redirect()->route('variants.index')->with('error', 'Variant with the same specifications and options already exists');
        }
    }
$existingspecifications = Varaint::with('VariantItems')
    ->where('brands_id', $request->input('brands_id'))
    ->where('master_model_lines_id', $request->input('master_model_lines_id'))
    ->where('coo', $request->input('coo'))
    ->where('my', $request->input('my'))
    ->where('drive_train', $request->input('drive_train'))
    ->where('gearbox', $request->input('gearbox'))
    ->where('upholestry', $request->input('upholestry'))
    ->whereHas('variantItems', function ($q) use ($selectedSpecifications) {
        $q->whereIn('model_specification_id', array_column($selectedSpecifications, 'specification_id'))
          ->whereIn('model_specification_options_id', array_column($selectedSpecifications, 'value'));
    })
    ->orderBy('created_at', 'desc')
    ->first();
    if ($existingspecifications) {
        $sematchedSpecifications = 0;
        foreach ($selectedSpecifications as $specificationData) {
            $matchFound = $existingspecifications->variantItems->contains(function ($variantItem) use ($specificationData) {
                return $variantItem->model_specification_id == $specificationData['specification_id'] &&
                       $variantItem->model_specification_options_id == $specificationData['value'];
            });
    
            if ($matchFound) {
                $sematchedSpecifications++;
            }
        }
        if ($sematchedSpecifications == $totalSpecifications) {
        $steering = $request->input('steering');
        if($steering == "LHD"){
            $steeringn = "L";
        }
        else{
            $steeringn = "R";
        }
        $master_model_lines_id = $request->input('master_model_lines_id');
        $engine = $request->input('engine');
        $fuel_type = $request->input('fuel_type');
        if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PHEV";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MHEV";
        }
        else if($fuel_type == "PH") 
        {
            $f = "PH";
        }
        else
        {
            $f = "EV";
        }
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        $existingName = $existingspecifications->name;
        $parts = explode('_', $existingName);
        
        if (count($parts) > 1) {
            $lastNumber = end($parts);
        
            if (is_numeric($lastNumber)) {
                $namepart = $steeringn . $model_line . $engine . $f;
                $newNumber = (int)$lastNumber;
                $name = $namepart . '_' . $newNumber;  // Use $namepart directly
            } else {
                $NewexistingName = substr($existingName, 0, -1);
                $parts = explode('_', $NewexistingName);
        
                if (count($parts) > 1) {
                    $lastNumber = end($parts);
        
                    if (is_numeric($lastNumber)) {
                        $namepart =  $steeringn . $model_line . $engine . $f;
                        $newNumber = (int)$lastNumber;
                        $name = $namepart . '_' . $newNumber;  // Use $namepart directly
                    } 
                }
            }
        }        
         else {
                $name = $existingName . '_1';
        }
    }
    else{
    $maxVariant = Varaint::where('brands_id', $request->input('brands_id'))
    ->where('master_model_lines_id', $request->input('master_model_lines_id'))
    ->where('fuel_type', $request->input('fuel_type'))
    ->where('engine', $request->input('engine'))
    ->where('steering', $request->input('steering'))
    ->orderBy('name', 'desc')
    ->first();
    $master_model_lines_id = $request->input('master_model_lines_id');
    $steering = $request->input('steering');
    if($steering == "LHD"){
        $steeringn = "L";
    }
    else{
        $steeringn = "R";
    }
    $engine = $request->input('engine');
    $fuel_type = $request->input('fuel_type');
    if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PHEV";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MHEV";
        }
        else if($fuel_type == "PH") 
        {
            $f = "PH";
        }
        else
        {
            $f = "EV";
        }
    $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
    if ($maxVariant) {
    $existingName = $maxVariant->name;
    $parts = explode('_', $existingName);
    if (count($parts) > 1) {
        $lastNumber = end($parts);
        if (is_numeric($lastNumber)) {
            $newNumber = (int)$lastNumber + 1;
            array_pop($parts);
            $name = implode('_', $parts) . '_' . $newNumber;
        } else {
            $NewexistingName = substr($existingName, 0, -1);
            $parts = explode('_', $NewexistingName);
            if (count($parts) > 1) {
                $lastNumber = end($parts);
                if (is_numeric($lastNumber)) {
                    $newNumber = (int)$lastNumber + 1;
                    array_pop($parts);
                    $name = implode('_', $parts) . '_' . $newNumber;
                } 
            }
        }
    } else {
            $name = $existingName . '_1';
    }
    } 
    else {
            $name = $steeringn . $model_line . $engine . $f . '_1';
    }
}
    }
    else{
        $maxVariant = Varaint::where('brands_id', $request->input('brands_id'))
        ->where('master_model_lines_id', $request->input('master_model_lines_id'))
        ->where('fuel_type', $request->input('fuel_type'))
        ->where('engine', $request->input('engine'))
        ->where('steering', $request->input('steering'))
        ->orderBy('name', 'desc')
        ->first();
        $master_model_lines_id = $request->input('master_model_lines_id');
        $steering = $request->input('steering');
        if($steering == "LHD"){
            $steeringn = "L";
        }
        else{
            $steeringn = "R";
        }
        $engine = $request->input('engine');
        $fuel_type = $request->input('fuel_type');
        if($fuel_type == "Petrol")
            {
                $f = "P";
            }
            else if($fuel_type == "Diesel") 
            {
                $f = "D";
            }
            else if($fuel_type == "PHEV") 
            {
                $f = "PHEV";
            }
            else if($fuel_type == "MHEV") 
            {
                $f = "MHEV";
            }
            else if($fuel_type == "PH") 
            {
                $f = "PH";
            }
            else
            {
                $f = "EV";
            }
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        if ($maxVariant) {
        $existingName = $maxVariant->name;
        $parts = explode('_', $existingName);
        if (count($parts) > 1) {
            $lastNumber = end($parts);
            if (is_numeric($lastNumber)) {
                $newNumber = (int)$lastNumber + 1;
                array_pop($parts);
                $name = implode('_', $parts) . '_' . $newNumber;
            } else {
                $NewexistingName = substr($existingName, 0, -1);
                $parts = explode('_', $NewexistingName);
                if (count($parts) > 1) {
                    $lastNumber = end($parts);
                    if (is_numeric($lastNumber)) {
                        $newNumber = (int)$lastNumber + 1;
                        array_pop($parts);
                        $name = implode('_', $parts) . '_' . $newNumber;
                    } 
                }
            }
        } else {
                $name = $existingName . '_1';
        }
        } 
        else {
                $name = $steeringn . $model_line . $engine . $f . '_1';
        }
    }
    (new UserActivityController)->createActivity('Creating New Variant');
    $model_details= $request->input('model_detail');
    if($model_details == null){
    $steering = $request->input('steering');
    $master_model_lines_id = $request->input('master_model_lines_id');
    $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
    $engine = $request->input('engine');
    $gearbox = $request->input('gearbox');
    $fuel_type = $request->input('fuel_type');
    if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PHEV";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MHEV";
        }
        else if($fuel_type == "PH") 
        {
            $f = "PH";
        }
        else
        {
            $f = "EV";
        }
    $model_details = $steering . ' ' . $model_line . ' ' . $engine . ' ' . $gearbox . ' ' . $fuel_type;
    }
    $variant_details= $request->input('variant');
    if($variant_details == null)
    {
        $steering = $request->input('steering');
        $master_model_lines_id = $request->input('master_model_lines_id');
        $model_line = MasterModelLines::where('id', $master_model_lines_id)->pluck('model_line')->first();
        $engine = $request->input('engine');
        $gearbox = $request->input('gearbox');
        $coo = $request->input('coo');
        $my = $request->input('my');
        $drive_train = $request->input('drive_train');
        $upholestry = $request->input('upholestry');
        $fuel_type = $request->input('fuel_type');
        if($fuel_type == "Petrol")
        {
            $f = "P";
        }
        else if($fuel_type == "Diesel") 
        {
            $f = "D";
        }
        else if($fuel_type == "PHEV") 
        {
            $f = "PHEV";
        }
        else if($fuel_type == "MHEV") 
        {
            $f = "MHEV";
        }
        else if($fuel_type == "PH") 
        {
            $f = "PH";
        }
        else
        {
            $f = "EV";
        }
        $variant_details = $my . ',' . $steering . ',' . $model_line . ',' . $engine . ',' . $gearbox . ',' . $fuel_type . ',' . $gearbox . ',' . $coo . ',' . $drive_train . ',' . $upholestry;
    }
    $variant = new Varaint();
    $variant->brands_id = $request->input('brands_id');
    $variant->master_model_lines_id = $request->input('master_model_lines_id');
    $variant->steering = $request->input('steering');
    $variant->fuel_type = $request->input('fuel_type');
    $variant->engine = $request->input('engine');
    $variant->upholestry = $request->input('upholestry');
    $variant->coo = $request->input('coo');
    $variant->drive_train = $request->input('drive_train');
    $variant->gearbox = $request->input('gearbox');
    $variant->name = $name;
    $variant->model_detail = $model_details;
    $variant->detail = $variant_details;
    $variant->my = $request->input('my');
    $variant->save();
    $variantId = $variant->id;
    foreach ($selectedSpecifications as $specificationData) {
        $specification = new VariantItems();
        $specification->varaint_id = $variantId;
        $specification->model_specification_id = $specificationData['specification_id'];
        $specification->model_specification_options_id = $specificationData['value'];
        $specification->save();
    }
    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
    $currentDateTime = Carbon::now($dubaiTimeZone);
    $variantlog = new Variantlog();
    $variantlog->time = $currentDateTime->toTimeString();
    $variantlog->date = $currentDateTime->toDateString();
    $variantlog->status = 'New Created';
    $variantlog->variant_id = $variantId;
    $variantlog->created_by = auth()->user()->id;
    $variantlog->save();
    return redirect()->route('variants.index')->with('success', 'Variant added successfully.');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        (new UserActivityController)->createActivity('View Variant Details');
        $variant = Varaint::findOrFail($id);

        return view('variants.show',compact('variant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        (new UserActivityController)->createActivity('Duplicate the Variants');
        $variant = Varaint::findOrFail($id);
        $brand = Brand::findOrFail($variant->brands_id);
        $brands = Brand::all();
        $countries = CountryListFacade::getList('en');
        $masterModelLines = MasterModelLines::all();
        $masterModelLine = MasterModelLines::findOrFail($variant->master_model_lines_id);
        $variantItems = VariantItems::where('varaint_id', '=', $id)->get();
        $modelLineId = $masterModelLine->id;
        $specifications = ModelSpecification::where('master_model_lines_id', $modelLineId)->get();
        $data = [];
        foreach ($specifications as $specification) {
            $selectedOptions = VariantItems::where('varaint_id', $id)
                ->where('model_specification_id', $specification->id)
                ->pluck('model_specification_options_id')
                ->toArray();
            $options = ModelSpecificationOption::where('model_specification_id', $specification->id)->get();
    
            $data[] = [
                'specification' => $specification,
                'selectedOptions' => $selectedOptions,
                'options' => $options,
            ];
        }
        return view('variants.duplicate',compact('countries','variant','brand','brands','masterModelLines', 'variantItems', 'data', 'masterModelLine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Update Variant Details');
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'brands_id' => 'required',
            'master_model_lines_id' => 'required',

        ]);
        $variant = Varaint::findOrFail($id);
        $oldValues = $variant->toArray();
            $variant->name = $request->input('name');
            $variant->brands_id = $request->input('brands_id');
            $variant->master_model_lines_id = $request->input('master_model_lines_id');
            $variant->fuel_type = $request->input('fuel_type');
            $variant->gearbox = $request->input('gearbox');
            $variant->my = $request->input('my');
            $variant->detail = $request->input('detail');
            $variant->seat = $request->input('seat');
            $variant->model_detail = $request->input('model_detail');
            $variant->upholestry = $request->input('upholestry');
            $changes = [];
            foreach ($oldValues as $field => $oldValue) {
                if ($field !== 'created_at' && $field !== 'updated_at') {
                    $newValue = $variant->$field;
                    if ($oldValue != $newValue) {
                        $changes[$field] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }
            if (!empty($changes)) {
                $variant->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                foreach ($changes as $field => $change) {
                    $variantlog = new Variantlog();
                    $variantlog->time = $currentDateTime->toTimeString();
                    $variantlog->date = $currentDateTime->toDateString();
                    $variantlog->status = 'Update Values';
                    $variantlog->variant_id = $id;
                    $variantlog->field = $field;
                    $variantlog->old_value = $change['old_value'];
                    $variantlog->new_value = $change['new_value'];
                    $variantlog->created_by = auth()->user()->id;
                    $variantlog->save();
                }
                }
                return redirect()->route('variants.index')->with('success', 'Variant updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('Delete the variant');
        $variant = Varaint::findOrFail($id);
        $variant->delete();
 
        return response(true);
    }
    public function getSpecificationDetails($modelLineId)
    {
        $specifications = ModelSpecification::where('master_model_lines_id', $modelLineId)->get();
    $data = [];
    foreach ($specifications as $specification) {
        $options = ModelSpecificationOption::where('model_specification_id', $specification->id)->get();
        $data[] = [
            'specification' => $specification,
            'options' => $options,
        ];
    }
    return response()->json(['data' => $data]);
    }
    public function specification($id)
    {
        (new UserActivityController)->createActivity('Open Model Speification Page');
        $specifications = ModelSpecification::where('master_model_lines_id', $id)->get();
        $model_line_id = $id;
        return view('model-lines.specificationslist', compact('specifications', 'model_line_id'));
    }
    public function viewSpecification($id)
{
    $options = ModelSpecificationOption::where('model_specification_id', $id)->get();
    return response()->json(['options' => $options]);
}
public function saveOption(Request $request)
{
    $request->validate([
        'specificationId' => 'required|numeric',
        'newOption' => 'required|string|max:255',
    ]);
    $existingOption = ModelSpecificationOption::where('name', $request->input('newOption'))
        ->where('model_specification_id', $request->input('specificationId'))
        ->first();
    if ($existingOption) {
        return response()->json(['error' => 'Option already exists for the given specification.'], 422);
    }
    $specificationoptions = new ModelSpecificationOption();
    $specificationoptions->name = $request->input('newOption');
    $specificationoptions->model_specification_id = $request->input('specificationId');
    $specificationoptions->save();
    return response()->json(['option' => $specificationoptions, 'message' => 'Option added successfully'], 200);
}
public function savespecification(Request $request)
{
    $existingOption = ModelSpecification::where('name', $request->input('newSpecificationName'))
        ->where('master_model_lines_id', $request->input('model_line_id'))
        ->first();
    if ($existingOption) {
        return response()->json(['error' => 'Specification Already Existing'], 422);
    }
    $specificationoptions = new ModelSpecification();
    $specificationoptions->name = $request->input('newSpecificationName');
    $specificationoptions->master_model_lines_id = $request->input('model_line_id');
    $specificationoptions->save();
    $masterModelLineId = $request->input('model_line_id');
    $variants = Varaint::where('master_model_lines_id', $masterModelLineId)->get();
    foreach ($variants as $variant) {
        $vehicles = Vehicles::where('varaints_id', $variant->id)
        ->whereNull('gdn_id')
        ->whereNotNull('grn_id')
        ->get();
        foreach ($vehicles as $vehicle) {
            $vehicle->inspection_status = "Pending";
            $vehicle->save();
        }
    }
    return response()->json(['message' => 'Option added successfully'], 200);
}
}
