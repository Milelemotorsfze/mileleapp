<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\User;
use App\Models\UserActivities;
use Carbon\Carbon;

class LoginActivityController extends Controller
{
    public function listUsers() {
        return view('users.activity.loginactivity');
    }
    public function listUsersgetdata(Request $request) {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        $data = LogActivity::with('logineUser')->whereBetween('created_at', [$startDate, $endDate])->get();
        return response()->json(['data' => $data]);
    }
    public function listUsersgetdataac(Request $request) {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        $userId = $request->input('user_id');
        $data = User::join('user_activities', 'users.id', '=', 'user_activities.users_id')
        ->where('users.id', $userId)
        ->whereBetween('user_activities.created_at', [$startDate, $endDate])
        ->select('users.name', 'users.email', 'user_activities.created_at', 'user_activities.activity')
        ->get();
    return response()->json(['data' => $data]);
    }
}
