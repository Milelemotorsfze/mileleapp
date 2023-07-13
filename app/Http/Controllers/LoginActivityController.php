<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;

class LoginActivityController extends Controller
{
    public function listUsers()
    {

        $users = LogActivity::with('logineUser')->latest()->get();
        return view('users.activity.loginactivity',compact('users'));
    }
}
