<?php

namespace App\Http\Controllers;
use App\Models\ColorCode;
use App\Models\Colorlog;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Http\Controllers\UserActivityController;


use Illuminate\Http\Request;

class ColorCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open Colour Code Information Page');
        $colorcodes = ColorCode::orderBy('id','DESC')->get();
        return view('colours.index', compact('colorcodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Create New Colour Code Page');
        return view('colours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('Create New Colour Code');
        $this->validate($request, [
            'name' => 'string|required|max:255',
            'belong_to' => 'required',
            'parent' => 'required',
            // 'status' => 'required',
        ]);
        $name = $request->input('name');
        $belong_to = $request->input('belong_to');
        $existingColour = ColorCode::where('name', $name)->where('belong_to', $belong_to)
            ->where('code', $request->input('code'))->first();
        if ($existingColour) {
            return redirect()->back()->with('error', 'Colour with the same name and Belong To already exists.');
        }
        $colourcodes = new ColorCode();
        $colourcodes->name  = $name;
        $colourcodes->code = $request->input('code');
        $colourcodes->belong_to = $belong_to;
        $colourcodes->parent = $request->input('parent');
        // $colourcodes->status = $request->input('status');
        $colourcodes->created_by = auth()->user()->id;
        $colourcodes->save();
        $colorcodeId = $colourcodes->id;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $colorlog = new Colorlog();
        $colorlog->time = $currentDateTime->toTimeString();
        $colorlog->date = $currentDateTime->toDateString();
        $colorlog->status = 'New Created';
        $colorlog->colorcode_id = $colorcodeId;
        $colorlog->created_by = auth()->user()->id;
        $colorlog->save();
        $colorcodes = ColorCode::orderBy('id', 'DESC')->get();
        return view('colours.index')->with(compact('colorcodes'))->with('success', 'Variant added successfully.');
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
        (new UserActivityController)->createActivity('Open Colour Code Edit Page');
        $colorcodes = ColorCode::findOrFail($id);
        $colorlog = Colorlog::where('colorcode_id', $id)->orderBy('created_at', 'desc')->get();
        return view('colours.edit',compact('colorcodes','colorlog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Edit Colour Code');
            $this->validate($request, [
            'name' => 'string|required|max:255',
            'belong_to' => 'required',
            'parent' => 'required',
            // 'status' => 'required',
        ]);
        $name = $request->input('name');
        $belong_to = $request->input('belong_to');
        $existingColour = ColorCode::where('name', $name)
            ->where('belong_to', $belong_to)
            ->where('id', '!=', $id)
            ->where('code', $request->input('code'))
            ->first();
        if ($existingColour) {
            return redirect()->back()->with('error', 'Color with the same name and Belong To already exists.');
        }
        $colourcodes = ColorCode::findOrFail($id);
        $oldValues = $colourcodes->toArray();

        $colourcodes->name  = $name;
        $colourcodes->code = $request->input('code');
        $colourcodes->belong_to = $belong_to;
        $colourcodes->parent = $request->input('parent');
        // $colourcodes->status = $request->input('status');
        $changes = [];
        foreach ($oldValues as $field => $oldValue) {
            if ($field !== 'created_at' && $field !== 'updated_at') {
                $newValue = $colourcodes->$field;
                if ($oldValue !== $newValue) {
                    $changes[$field] = [
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }
        }

        if (!empty($changes)) {
        $colourcodes->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        foreach ($changes as $field => $change) {
        $colorlog = new Colorlog();
        $colorlog->time = $currentDateTime->toTimeString();
        $colorlog->date = $currentDateTime->toDateString();
        $colorlog->status = 'Update Values';
        $colorlog->colorcode_id = $id;
        $colorlog->field = $field;
        $colorlog->old_value = $change['old_value'];
        $colorlog->new_value = $change['new_value'];
        $colorlog->created_by = auth()->user()->id;
        $colorlog->save();
        }
    }
        $colorcodes = ColorCode::orderBy('id', 'DESC')->get();
        return view('colours.index')->with(compact('colorcodes'))->with('success', 'Variant added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
