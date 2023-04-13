<?php

namespace App\Http\Controllers;

use App\Models\blfrom;
use Illuminate\Http\Request;

class BLformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blform.index');
        $bldata = BLForm::all();
    }
    public function create()
    {
        return view('blform.create');
    }
    public function store()
    {
    }
}
