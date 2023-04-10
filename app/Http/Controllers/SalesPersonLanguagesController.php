<?php

namespace App\Http\Controllers;

use App\Models\SalesPersonLaugauges;
use Illuminate\Http\Request;

class SalesPersonLanguagesController extends Controller
{
    public function index()
    {
      
    }

    public function create()
    {
        return view('languages.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sales_person_id' => 'required|integer',
            'language' => 'required|string|max:255',
        ]);

        $salesPersonLanguage = SalesPersonLanguages::create($validatedData);

       // return redirect()->route('sales_person_languages.index');
    }

    public function edit(SalesPersonLanguages $salesPersonLanguage)
    {
       // return view('sales_person_languages.edit', compact('salesPersonLanguage'));
    }

    public function update(Request $request, SalesPersonLanguages $salesPersonLanguage)
    {
        // $validatedData = $request->validate([
        //     'sales_person_id' => 'required|integer',
        //     'language' => 'required|string|max:255',
        // ]);

        // $salesPersonLanguage->update($validatedData);

        // return redirect()->route('sales_person_languages.index');
    }

    public function destroy(SalesPersonLanguages $salesPersonLanguage)
    {
        // $salesPersonLanguage->delete();

        // return redirect()->route('sales_person_languages.index');
    }
}
