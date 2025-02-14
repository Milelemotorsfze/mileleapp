<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\DpColorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DpColorCodesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:color_codes,name',
            'belong_to' => 'required|string',
            'parent' => 'required|string',
            'color_codes' => 'required|array|min:1',
            'color_codes.*' => 'string|distinct'
        ]);

        DB::beginTransaction();
        try {
            // Step 1: Store data in color_codes table
            $colorCode = ColorCode::create([
                'name' => $request->input('name'),
                'belong_to' => $request->input('belong_to'),
                'parent' => $request->input('parent'),
                'created_by' => auth()->user()->id
            ]);

            // Step 2: Store data in dp_color_codes table
            foreach ($request->input('color_codes') as $code) {
                DpColorCode::create([
                    'color_code_id' => $colorCode->id,
                    'color_code_values' => $code,
                    'created_by' => auth()->user()->id
                ]);
            }

            DB::commit();
            return redirect()->route('colourcode.index')->with('success', 'Color codes added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing color codes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add color codes. Please try again.');
        }
    }
}
