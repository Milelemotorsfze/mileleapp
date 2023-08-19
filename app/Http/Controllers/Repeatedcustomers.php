<?php

namespace App\Http\Controllers;
use App\Models\Calls;
use Illuminate\Http\Request;

class Repeatedcustomers extends Controller
{
    public function repeatedcustomers(Request $request)
    {
        $phone = $request->query('phone');
        $email = $request->query('email');
        if($phone != null && $email == null)
        {
        $calls = Calls::where('phone', $phone)
                ->get();
        }
        else if($phone == null && $email != null)
        {
        $calls = Calls::where('email', $email)
                ->get();
        }
        else{
            $calls = Calls::where('email', $email)
                    ->orwhere('phone', $phone)
                ->get(); 
        }
        return view('calls.repeatedcustomer', compact('calls'));
    }
}
