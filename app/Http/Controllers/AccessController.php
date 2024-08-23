<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function notAccessPage()
    {
        return view('errors.not_access');
    }
}
