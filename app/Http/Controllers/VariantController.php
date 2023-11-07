<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModelLines;
use App\Models\Varaint;
use App\Models\Variantlog;
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
        $masterModelLines = MasterModelLines::all();
        return view('variants.create', compact('masterModelLines','brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    (new UserActivityController)->createActivity('Creating New Variant');
    $this->validate($request, [
        'name' => 'string|required|max:255',
        'detail' => 'required',
        'engine' => 'required',
    ]);
    $name = $request->input('name');
    $existingVariant = Varaint::where('name', $name)->first();
    if ($existingVariant) {
        return redirect()->back()->with('error', 'Variant with the same name already exists.');
    }
    $variant = new Varaint();
    $variant->name  = $name;
    $variant->brands_id  = $request->input('brands_id');
    $variant->master_model_lines_id = $request->input('master_model_lines_id');
    $variant->steering = $request->input('steering');
    $variant->engine = $request->input('engine');
    $variant->fuel_type = $request->input('fuel_type');
    $variant->gearbox = $request->input('gearbox');
    $variant->my = $request->input('my');
    $variant->detail = $request->input('detail');
    $variant->seat = $request->input('seat');
    $variant->model_detail = $request->input('model_detail');
    $variant->upholestry = $request->input('upholestry');
    $variant->save();
    $variantId = $variant->id;
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
        (new UserActivityController)->createActivity('Edit Variant Details page View');
        $brands = Brand::all();
        $masterModelLines = MasterModelLines::all();
        $variant = Varaint::findOrFail($id);
        $variantlog = Variantlog::where('variant_id', $id)->orderBy('created_at', 'desc')->get();
        return view('variants.edit',compact('variant','brands','masterModelLines', 'variantlog'));
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
}
