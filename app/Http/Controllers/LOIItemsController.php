<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItems;
use Illuminate\Http\Request;

class LOIItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $letterOfIndent = LetterOfIndent::find($request->id);
        $letterOfIndentItems = LetterOfIndentItems::where('letter_of_indent_id', $request->id)->get();
        return view('letter-of-indent-items.create',compact('letterOfIndent','letterOfIndentItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
