<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddonSellingPrice;

class ApprovalAwaitingController extends Controller {

    public function addonApprovalAwaiting($type) {
        $pendings = AddonSellingPrice::where('status','pending')->whereHas('addonDetail', function($q) use($type) {
            $q->where('addon_type_name',$type);
        })->latest('updated_at')->get();
        foreach($pendings as $pending) {  
            $pending->currentPrice = '';
            $currentPrice = AddonSellingPrice::where('addon_details_id',$pending->addon_details_id)->where('status','active')->select('selling_price')->first();
            if($currentPrice) {
                $pending->currentPrice = $currentPrice->selling_price;
            }
        }
        $approved = AddonSellingPrice::whereIn('status',['active','inactive'])->whereHas('addonDetail', function($q) use($type) {
            $q->where('addon_type_name',$type);
        })->latest('updated_at')->get();
        $rejected = AddonSellingPrice::where('status','rejected')->whereHas('addonDetail', function($q) use($type) {
            $q->where('addon_type_name',$type);
        })->latest('updated_at')->get();
        return view('approvals.index',compact('pendings','approved','rejected','type'));
    }
}
