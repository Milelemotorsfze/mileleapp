<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BLformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    	echo "BL Form";
    }
    public function create()
    {
    	return view('blfrom.create');
    }
    public function store()
    {
    }
}
