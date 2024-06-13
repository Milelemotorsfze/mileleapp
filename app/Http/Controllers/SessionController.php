<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function status()
    {
        return response()->json(['authenticated' => Auth::check()]);
    }
}

