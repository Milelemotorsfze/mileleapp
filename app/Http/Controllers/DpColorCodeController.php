<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use App\Models\DpColorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DpColorCodesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:color_codes,name',
            'belong_to' => 'required|string',
            'parent' => 'required|string',
            'color_codes' => 'nullable|array',
            'color_codes.*' => 'string|distinct'
        ]);

        DB::beginTransaction();
        try {
            $colorCode = ColorCode::create([
                'name' => $request->input('name'),
                'belong_to' => $request->input('belong_to'),
                'parent' => $request->input('parent'),
                'created_by' => auth()->user()->id
            ]);

            foreach ($request->input('color_codes', []) as $code) {
                if (!empty($code)) {
                    DpColorCode::create([
                        'color_code_id' => $colorCode->id,
                        'color_code_values' => $code,
                        'created_by' => auth()->user()->id
                    ]);
                }
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
