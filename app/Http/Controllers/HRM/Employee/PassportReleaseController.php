<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\PassportReleaseHistory;

class PassportReleaseController extends Controller
{
    public function index() {
        $pendings = PassportRelease::where('release_submit_status','pending')->latest()->get();
        $approved = PassportRelease::where('release_submit_status','approved')->latest()->get();
        $rejected = PassportRelease::where('release_submit_status','rejected')->latest()->get();
        return view('hrm.passport.passport_release.index',compact('pendings','approved','rejected'));
    }
}
