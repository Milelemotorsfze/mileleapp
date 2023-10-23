<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;
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
}
