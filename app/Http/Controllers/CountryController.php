<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{

    public function create()
    {
        return view('countries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('countries')->where(function ($query) use ($request) {
                    return $query
                        ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                        ->whereNull('deleted_at');
                }),
            ],
            'nationality' => 'nullable|string|max:255',
            'iso_3166_code' => 'nullable|string|max:255',
            'is_african_country' => 'required|boolean',
        ]);

        $country = new Country($request->all());
        $country->created_by = Auth::id();
        $country->is_african_country = $request->has('is_african_country') ? $request->is_african_country : false;
        $country->save();

        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('countries')->ignore($country->id)->where(function ($query) use ($request) {
                    return $query
                        ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                        ->whereNull('deleted_at');
                }),
            ],
            'nationality' => 'nullable|string|max:255',
            'iso_3166_code' => 'nullable|string|max:255',
            'is_african_country' => 'required|boolean',
        ]);

        $country->fill($request->all());
        $country->updated_by = auth()->id();
        $country->is_african_country = $request->has('is_african_country') ? $request->is_african_country : false;
        $country->save();

        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }
    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }
}
