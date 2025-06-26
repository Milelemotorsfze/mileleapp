<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'vehicles';
    public const VEHICLE_STATUS_INCOMING = 'Approved';
    public const FILTER_PREVIOUS_YEAR_SOLD = 'PREVIOUS YEAR SOLD';
    public const FILTER_PREVIOUS_MONTH_SOLD = 'PREVIOUS MONTH SOLD';
    public const FILTER_YESTERDAY_SOLD = 'YESTERDAY SOLD';
    public const FILTER_PREVIOUS_YEAR_BOOKED = 'PREVIOUS YEAR BOOKED';
    public const FILTER_PREVIOUS_MONTH_BOOKED = 'PREVIOUS MONTH BOOKED';
    public const FILTER_YESTERDAY_BOOKED = 'YESTERDAY BOOKED';
    public const FILTER_PREVIOUS_YEAR_AVAILABLE = 'PREVIOUS YEAR AVAILABLE';
    public const FILTER_PREVIOUS_MONTH_AVAILABLE = 'PREVIOUS MONTH AVAILABLE';
    public const FILTER_YESTERDAY_AVAILABLE = 'YESTERDAY AVAILABLE';
    public const FILTER_YESTERDAY_PURCHASED = 'YESTERDAY PURCHASED';
    public const FILTER_PREVIOUS_MONTH_PURCHASED = 'FILTER PREVIOUS MONTH PURCHASED';
    public const FILTER_PREVIOUS_YEAR_PURCHASED = 'FILTER PREVIOUS YEAR PURCHASED';
    // public  $appends = [
    //     // 'similar_vehicles_with_active_stock',
    //     // 'similar_vehicles_with_inactive_stock',
    // //     'old_price',
    // //     'old_price_dated',
    // //     'updated_by',
    // //     'active_vehicle_price_status',
    // //     'inactive_vehicle_price_status'
    // ];
    protected $fillable = [
        'varaints_id',
        'int_colour',
        'ex_colour',
        'engine',
        'ppmmyyy',
        'reservation_start_date',
        'reservation_end_date',
        'conversion',
        'inspection_date',
        'procurement_vehicle_remarks',
        'purchased_paid_percentage',

    ];
    public function dn()
    {
        return $this->belongsTo(VehicleDn::class, 'dn_id');
    }
    public function variant()
    {
        return $this->belongsTo(Varaint::class, 'varaints_id');
    }
    public function interior()
    {
        return $this->belongsTo(ColorCode::class, 'int_colour', 'id');
    }
    public function exterior()
    {
        return $this->belongsTo(ColorCode::class, 'ex_colour', 'id');
    }
    public function purchasingOrder()
    {
        return $this->belongsTo(PurchasingOrder::class, 'purchasing_order_id');
    }
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class, 'model_id', 'id');
    }
    public function vehiclePurchasingCost()
    {
        return $this->hasOne(VehiclePurchasingCost::class, 'vehicles_id');
    }
        public function purchasingCosts()
    {
        return $this->hasMany(VehiclePurchasingCost::class, 'vehicles_id');
    }

    public function latestRemarkSales()
    {
        return $this->hasOne(Remarks::class)
            ->where('department', 'sales')
            ->orderByDesc('created_at');
    }

    public function latestRemarkWarehouse()
    {
        return $this->hasOne(Remarks::class)
            ->where('department', 'warehouse')
            ->orderByDesc('created_at');
    }
    public function vehicleDetailApprovalRequests()
    {
        return $this->hasMany(VehicleApprovalRequests::class, 'vehicle_id', 'id');
    }
    public function so()
    {
        return $this->belongsTo(So::class, 'so_id');
    }
    public function grn()
    {
        return $this->belongsTo(Grn::class, 'grn_id');
    }
    public function movementGrn()
    {
        return $this->belongsTo(MovementGrn::class, 'movement_grn_id');
    }
    public function gdn()
    {
        return $this->belongsTo(Gdn::class, 'gdn_id');
    }
    public function document()
    {
        return $this->belongsTo(Document::class, 'documents_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'latest_location');
    }
    public function warehouseLocation()
    {
        return $this->belongsTo(Warehouse::class, 'latest_location', 'id');
    }
    public function woVehicle()
    {
        return $this->hasMany(WOVehicles::class, 'vehicle_id', 'id');
    }
    public function incidents()
    {
        return $this->hasMany(Incident::class, 'vehicle_id');
    }

    public function pdis()
    {
        return $this->hasMany(Pdi::class, 'vehicle_id');
    }

    public function netsuiteCosts()
    {
        return $this->hasMany(VehicleNetsuiteCost::class, 'vehicles_id');
    }
    public function purchasedOrderChanges()
    {
        return $this->hasMany(PurchasedOrderPriceChanges::class, 'purchasing_order_id', 'purchasing_order_id');
    }

    // public function getSimilarVehiclesWithInactiveStockAttribute()
    // {
    //     // dd($this->varaints_id);
    //     $vehicles = Vehicles::whereNotNull('gdn_id')
    //         ->where('payment_status', Vehicles::VEHICLE_STATUS_INCOMING)
    //         ->where('varaints_id', $this->varaints_id)
    //         ->groupBy('int_colour', 'ex_colour')
    //         ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
    //         ->get();
    //     $vehicles = $vehicles->sortBy('price_status');

    //     return $vehicles;
    // }
    // public function getSimilarVehiclesWithActiveStockAttribute() {
    //     $vehicles =  Vehicles::whereNull('gdn_id')
    //         ->where('payment_status', Vehicles::VEHICLE_STATUS_INCOMING)
    //         ->where('varaints_id', $this->varaints_id)
    //         ->groupBy('int_colour','ex_colour')
    //         ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
    //         ->get();
    //     $vehicles = $vehicles->sortBy('price_status');

    //     return $vehicles;
    // }

    // public function getOldPriceAttribute() {

    //     $vehicle = Vehicles::find($this->id);
    //     $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //         ->where('int_colour', $vehicle->int_colour)
    //         ->where('ext_colour', $vehicle->ex_colour)
    //         ->first();
    //     if(!empty($availableColour)) {
    //         $latestPriceHistory = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
    //             ->orderBy('id','DESC')
    //             ->first();
    //         if(!empty($availableColour)) {
    //             return $latestPriceHistory->old_price;
    //         }
    //         return 0;
    //     }
    //     return 0;
    // }
    // public function getActiveVehiclePriceStatusAttribute() {

    //     $similarVehicles = Vehicles::whereNull('gdn_id')
    //         ->where('varaints_id', $this->varaints_id)
    //         ->where('int_colour', $this->int_colour)
    //         ->where('ex_colour', $this->ex_colour)
    //         ->pluck('id');

    //     $priceStatus = [];

    //     foreach ($similarVehicles as $similarVehicle) {
    //         $vehicle = Vehicles::find($similarVehicle);
    //         $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //             ->where('int_colour', $vehicle->int_colour)
    //             ->where('ext_colour', $vehicle->ex_colour)
    //             ->first();
    //         if(!empty($availableColour)) {
    //             $priceStatus[] = 'true';
    //         }else{
    //             $priceStatus[] = 'false';
    //         }
    //     }
    //     if (array_unique($priceStatus) === array('true')) {
    //         return 1;
    //     }else{
    //         return 0;
    //     }
    // }
    // public function getInactiveVehiclePriceStatusAttribute() {
    //     $similarVehicles = Vehicles::whereNotNull('gdn_id')
    //         ->where('varaints_id', $this->varaints_id)
    //         ->where('int_colour', $this->int_colour)
    //         ->where('ex_colour', $this->ex_colour)
    //         ->pluck('id');
    //     $priceStatus = [];
    //     foreach ($similarVehicles as $similarVehicle) {
    //         $vehicle = Vehicles::find($similarVehicle);
    //         $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //             ->where('int_colour', $vehicle->int_colour)
    //             ->where('ext_colour', $vehicle->ex_colour)
    //             ->first();
    //         if(!empty($availableColour)) {
    //             $priceStatus[] = 'true';
    //         }else{
    //             $priceStatus[] = 'false';
    //         }
    //     }
    //     if (array_unique($priceStatus) === array('true')) {
    //         return 1;
    //     }else{
    //         return 0;
    //     }
    // }
    // public function getOldPriceDatedAttribute() {

    //     $vehicle = Vehicles::find($this->id);
    //     $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //         ->where('int_colour', $vehicle->int_colour)
    //         ->where('ext_colour', $vehicle->ex_colour)
    //         ->first();
    //     if(!empty($availableColour)) {
    //         $latestPriceHistory = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
    //             ->orderBy('id', 'DESC')
    //             ->get();
    //         if ($latestPriceHistory->count() > 1 ) {
    //             $latestPriceHistory = $latestPriceHistory->skip(1)->first();
    //             return Carbon::parse($latestPriceHistory->updated_at)->format('d/m/y, H:i:s');
    //         }

    //     }
    //         return "";
    // }
    // public function getUpdatedByAttribute() {

    //     $vehicle = Vehicles::find($this->id);
    //     $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //         ->where('int_colour', $vehicle->int_colour)
    //         ->where('ext_colour', $vehicle->ex_colour)
    //         ->first();
    //     if(!empty($availableColour)) {
    //         $updatedBy = $availableColour->user->name;
    //        return $updatedBy;
    //     }
    //     return " ";
    // }
    // public function getUpdatedAtAttribute() {

    //     $vehicle = Vehicles::find($this->id);
    //     $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
    //         ->where('int_colour', $vehicle->int_colour)
    //         ->where('ext_colour', $vehicle->ex_colour)
    //         ->first();
    //     if(!empty($availableColour)) {
    //         $updatedAt = $availableColour->updated_at;
    //         return Carbon::parse($updatedAt)->format('d/m/Y, H:i:s');
    //         return  ;
    //     }
    //     return " ";
    // }
}
