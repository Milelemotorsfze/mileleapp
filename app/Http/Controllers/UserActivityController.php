<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    public function createActivity($activity)   { 
        $useractivities =  New UserActivities();
        $useractivities->activity = $activity;
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    }
}
