<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentDocument;
use App\Models\LetterOfIndentItem;
use Illuminate\Http\Request;

class LOIDocumentsController extends Controller
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
        $letterOfIndent = LetterOfIndent::findOrFail($request->letter_of_indent_id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)->get();
        $letterOfIndentDocuments = LetterOfIndentDocument::where('letter_of_indent_id', $letterOfIndent->id)->get();

//        return $letterOfIndentDocuments;

        return view('letter-of-indent-documents.create', compact('letterOfIndent','letterOfIndentItems',
        'letterOfIndentDocuments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

//        return $request->letter_of_indent_id;
//        $request->validate([
//            'files' => 'required'
//        ]);
        $LoiDocument = new LetterOfIndentDocument();
        $LoiDocument->letter_of_indent_id = $request->letter_of_indent_id;

        if (($request->has('files'))) {
            $files = $request->file('files');

            $destinationPath = 'LOI-Documents';
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'_'.$extension;
                $file->move($destinationPath, $fileName);
                $LoiDocument->loi_document_file = $fileName;
            }
        }

        $LoiDocument->save();

        return redirect()->route('letter-of-indent-documents.create',['letter_of_indent_id' => $request->letter_of_indent_id]);
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
