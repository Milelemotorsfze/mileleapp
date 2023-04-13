<?php

namespace App\Http\Controllers;

use App\Models\blform;
use Illuminate\Http\Request;

class BLformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blformvins.index');
        $bldata = BLForm::all();
    }
    public function create()
    {
        return view('blformvins.createvin');
    }
    public function store()
    {
    }
}
