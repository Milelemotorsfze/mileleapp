<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingOrder extends Model
{
    use HasFactory;
    protected $table = 'purchasing_order';
    protected $appends = [
        'is_demand_planning_purchase_order',

    ];
    public function purchasing_order_items()
    {
        return $this->hasMany(PurchasingOrderItems::class);
    }
    public function vehicles()
    {
        return $this->hasMany(Vehicles::class);
    }
    public function LOIPurchasingOrder()
    {
        return $this->hasOne(LOIItemPurchaseOrder::class, 'purchase_order_id');
    }
    public function getIsDemandPlanningPurchaseOrderAttribute() {
        $isDemandPlanningPO = LOIItemPurchaseOrder::where('purchase_order_id', $this->id)->count();
        if($isDemandPlanningPO > 0) {
            return true;
        }
        return false;
    }
    public function polPort()
    {
        return $this->belongsTo(MasterShippingPorts::class, 'pol');
    }

    public function podPort()
    {
        return $this->belongsTo(MasterShippingPorts::class, 'pod');
    }

    public function fdCountry()
    {
        return $this->belongsTo(Country::class, 'fd');
    }
}
