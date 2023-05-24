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

        if (($request->has('files')))
        {
            foreach ($request->file('files') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extension;
                $destinationPath = 'LOI-Documents';
                $file->move($destinationPath, $fileName);

                $LoiDocument = new LetterOfIndentDocument();

                $LoiDocument->loi_document_file = $fileName;
                $LoiDocument->letter_of_indent_id = $request->letter_of_indent_id;
                $LoiDocument->save();
            }
        }

        if ($request->page_name == 'EDIT-PAGE') {
            return redirect()->route('letter-of-indent-documents.edit', $request->letter_of_indent_id);
        }

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
        $letterOfIndent = LetterOfIndent::findOrFail($id);
        $letterOfIndentItems = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndent->id)->get();
        $letterOfIndentDocuments = LetterOfIndentDocument::where('letter_of_indent_id', $letterOfIndent->id)->get();

        return view('letter-of-indent-documents.edit', compact('letterOfIndent','letterOfIndentItems',
            'letterOfIndentDocuments'));
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
        $letterOfIndentDocument = LetterOfIndentDocument::find($id);
        $letterOfIndentDocument->delete();
        return true;
    }
}
