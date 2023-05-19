<?php

namespace App\Http\Controllers;
use App\Models\calls;
use Illuminate\Http\Request;

class Repeatedcustomers extends Controller
{
    public function repeatedcustomers(Request $request)
    {
        $phone = $request->query('phone');
        $email = $request->query('email');
        
        $calls = Calls::where('phone', $phone)
            ->orWhere('email', $email)
            ->get();
        
        return view('calls.repeatedcustomer', compact('calls'));
    }
}
