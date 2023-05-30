<?php

namespace App\Http\Controllers;

use App\Models\SupplierAddonTemp;
use Illuminate\Http\Request;
use App\Exports\SupplierAddonTempExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreSupplierAddonRequest;

class SupplierAddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = SupplierAddonTemp::paginate(5);       
        return view('student.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $student = new SupplierAddonTemp;
        $student->addon_code = $request->addon_code;
        $student->currency = $request->currency;
        $student->purchase_price = $request->purchase_price;
        $student->save();
        return redirect(route('student.index'))->with('success','Data submited successfully!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function get_student_data()
    {
        return Excel::download(new SupplierAddonTempExport, 'supplier_addon_price.xlsx');
    }
}
