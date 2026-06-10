<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Use_;

class PurchasingOrder extends Model
{
    use HasFactory;
    protected $table = 'purchasing_order';

    public const PAYMENT_STATUS_PENDING = 'Pending';
    public const PAYMENT_STATUS_INITIATED = 'Initiated';
    public const PAYMENT_STATUS_PARTIALY_PAID = 'Partially Paid';
    public const PAYMENT_STATUS_PAID = 'Paid';
    public const PAYMENT_STATUS_UNPAID = 'Unpaid';

    protected $fillable = [
        'po_number',
        'po_date',
        'is_demand_planning_po',
        'created_by',
        'status',
        'po_type',
        'vendors_id',
        'payment_term_id',
        'currency',
        'shippingmethod',
        'shippingcost',
        'totalcost',
        'pol',
        'pod',
        'fd',
        'remarks',
        'pl_number',
        'pl_file_path',
    ];

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
    // need to remove
    public function LOIPurchasingOrder()
    {
        return $this->hasOne(LOIItemPurchaseOrder::class, 'purchase_order_id');
    }
    public function PfiItemPurchaseOrders() {
        return $this->hasMany(PfiItemPurchaseOrder::class,'purchase_order_id');
    }
    public function PFIPurchasingOrder()
    {
        return $this->hasOne(PfiItemPurchaseOrder::class, 'purchase_order_id');
    }
    public function getIsDemandPlanningPurchaseOrderAttribute() {
        $isDemandPlanningPO = PfiItemPurchaseOrder::where('purchase_order_id', $this->id)->count();
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
    public function purchasedOrderPaidAmounts()
    {
        return $this->hasMany(PurchasedOrderPaidAmounts::class, 'purchasing_order_id');
    }
    public function SupplierAccountTransactions()
    {
        return $this->hasMany(SupplierAccountTransaction::class, 'purchasing_order_id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'vendors_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
