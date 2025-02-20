<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\Colorlog;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Http\Controllers\UserActivityController;
use App\Models\DpColorCode;
use App\Models\ParentColour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ColorCodesController extends Controller
{
    public function index()
    {
        (new UserActivityController)->createActivity('Open Colour Code Information Page');
        $colorcodes = ColorCode::with(['dpColorCodes', 'createdBy'])->orderBy('color_codes.id', 'DESC')->get();

        foreach ($colorcodes as $colorcode) {
            $dpCodes = $colorcode->dpColorCodes->pluck('color_code_values')->join(', ');
            $colorcode->dpCodes = $dpCodes;
        }
        $ids = $colorcodes->pluck('id');
        Log::info('Ordered IDs:', $ids->toArray());

        return view('colours.index', compact('colorcodes'));
    }


    public function create()
    {
        (new UserActivityController)->createActivity('Open Create New Colour Code Page');
        $parentColours = ParentColour::all();

        return view('colours.create', compact('parentColours'));
    }

    public function store(Request $request)
    {
        Log::info('Received form data:', $request->all());
        
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if (ColorCode::where('name', $value)
                        ->where('belong_to', $request->belong_to)
                        ->exists()
                    ) {
                        $fail('The color name already exists for the selected category.');
                    }
                },
            ],
            'belong_to' => 'required|string',
            'parent_colour_id' => 'required|exists:parent_colours,id',
            'color_codes' => 'nullable|array',
            'color_codes.*' => 'string|distinct'
        ], [
            'name.unique' => 'The color name already exists.',
            'parent_colour_id.required' => 'Please select a valid parent color.',
            'parent_colour_id.exists' => 'Invalid parent color selected.',
        ]);

        DB::beginTransaction();
        try {
            $colorCode = ColorCode::create([
                'name' => $request->input('name'),
                'belong_to' => $request->input('belong_to'),
                'parent_colour_id' => $request->input('parent_colour_id'),
                'created_by' => auth()->user()->id
            ]);

            if ($request->filled('color_codes')) {
                foreach ($request->color_codes as $code) {
                    if (!empty($code)) {
                        DpColorCode::create([
                            'color_code_id' => $colorCode->id,
                            'color_code_values' => $code,
                            'created_by' => auth()->user()->id
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('colourcode.index')->with('success', 'Color codes added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add color codes. Please try again.');
        }
    }



    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        (new UserActivityController)->createActivity('Open Colour Code Edit Page');
        $colorcodes = ColorCode::findOrFail($id);
        $colorlog = Colorlog::where('colorcode_id', $id)->orderBy('created_at', 'desc')->get();
        return view('colours.edit', compact('colorcodes', 'colorlog'));
    }

    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Edit Colour Code');
    
        $request->validate([
            'name' => [
                'string',
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($request, $id) {
                    if (ColorCode::where('name', $value)
                        ->where('belong_to', $request->belong_to) // Consider belong_to
                        ->where('id', '!=', $id) // Ignore current color
                        ->exists()
                    ) {
                        $fail('The color name already exists for the selected category.');
                    }
                },
            ],
            'belong_to' => 'required',
            'parent' => 'required',
        ]);
    
        $colourcodes = ColorCode::findOrFail($id);
        $oldValues = $colourcodes->toArray();
    
        $colourcodes->name  = $request->input('name');
        $colourcodes->code = $request->input('code');
        $colourcodes->belong_to = $request->input('belong_to');
        $colourcodes->parent = $request->input('parent');
    
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
    
        return redirect()->route('colourcode.index')->with('success', 'Color updated successfully.');
    }
    

    public function destroy(string $id)
    {
        //
    }

    public function checkName(Request $request)
    {
        $exists = ColorCode::where('name', $request->name)
            ->where('belong_to', $request->belong_to)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
