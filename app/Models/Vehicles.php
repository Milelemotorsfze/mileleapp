<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    public const VEHICLE_STATUS_INCOMING = 'Incoming Stock';

    public const FILTER_PREVIOUS_YEAR_SOLD = 'PREVIOUS YEAR SOLD';
    public const FILTER_PREVIOUS_MONTH_SOLD = 'PREVIOUS MONTH SOLD';
    public const FILTER_YESTERDAY_SOLD = 'YESTERDAY SOLD';
    public const FILTER_PREVIOUS_YEAR_BOOKED = 'PREVIOUS YEAR BOOKED';
    public const FILTER_PREVIOUS_MONTH_BOOKED = 'PREVIOUS MONTH BOOKED';
    public const FILTER_YESTERDAY_BOOKED = 'YESTERDAY BOOKED';
    public const FILTER_PREVIOUS_YEAR_AVAILABLE = 'PREVIOUS YEAR AVAILABLE';
    public const FILTER_PREVIOUS_MONTH_AVAILABLE = 'PREVIOUS MONTH AVAILABLE';
    public const FILTER_YESTERDAY_AVAILABLE = 'YESTERDAY AVAILABLE';

    public  $appends = [
        'similar_vehicles_with_active_stock',
        'similar_vehicles_with_inactive_stock',
        'old_price',
        'old_price_dated',
        'updated_by',
        'price_status'
    ];
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

    ];
    public function variant()
    {
        return $this->belongsTo(Varaint::class,'varaints_id','id');
    }
    public function interior()
    {
        return $this->belongsTo(ColorCode::class,'int_colour','id');
    }
    public function exterior()
    {
        return $this->belongsTo(ColorCode::class,'ex_colour','id');
    }
    public function vehicleDetailApprovalRequests()
    {
        return $this->hasMany(VehicleApprovalRequests::class,'vehicle_id','id');
    }
    public function so()
    {
        return $this->belongsTo(So::class, 'so_id');
    }
    public function getSimilarVehiclesWithInactiveStockAttribute()
    {
        $vehicles = Vehicles::whereNotNull('gdn_id')
            ->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
            ->where('varaints_id', $this->varaints_id)
            ->groupBy('int_colour', 'ex_colour')
            ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
            ->get();
        $vehicles = $vehicles->sortBy('price_status');

        return $vehicles;
    }
    public function getSimilarVehiclesWithActiveStockAttribute() {
        $vehicles =  Vehicles::whereNull('gdn_id')
            ->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
            ->where('varaints_id', $this->varaints_id)
            ->groupBy('int_colour','ex_colour')
            ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
            ->get();
        $vehicles = $vehicles->sortBy('price_status');

        return $vehicles;
    }

    public function getOldPriceAttribute() {

        $vehicle = Vehicles::find($this->id);
        $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->where('int_colour', $vehicle->int_colour)
            ->where('ext_colour', $vehicle->ex_colour)
            ->first();
        if(!empty($availableColour)) {
            $latestPriceHistory = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
                ->orderBy('id','DESC')
                ->first();
            if(!empty($availableColour)) {
                return $latestPriceHistory->old_price;
            }
            return 0;
        }
        return 0;
    }
    public function getPriceStatusAttribute() {

        $similarVehicles = Vehicles::whereNull('gdn_id')
            ->where('varaints_id', $this->varaints_id)
            ->groupBy('int_colour','ex_colour')
            ->pluck('id');
        $priceStatus = [];

        foreach ($similarVehicles as $similarVehicle) {
            $vehicle = Vehicles::find($similarVehicle);
            $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                ->where('int_colour', $vehicle->int_colour)
                ->where('ext_colour', $vehicle->ex_colour)
                ->first();
            if(!empty($availableColour)) {
                $priceStatus[] = 'true';
            }else{
                $priceStatus[] = 'false';
            }
        }
        if (array_unique($priceStatus) === array('true')) {
//            return 'Available';
            return 1;
        }else{
//            return 'Not Available';
            return 0;
        }
    }
    public function getOldPriceDatedAttribute() {

        $vehicle = Vehicles::find($this->id);
        $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->where('int_colour', $vehicle->int_colour)
            ->where('ext_colour', $vehicle->ex_colour)
            ->first();
        if(!empty($availableColour)) {
            $latestPriceHistory = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
                ->orderBy('id', 'DESC')
                ->get();
            if ($latestPriceHistory->count() > 1 ) {
                $latestPriceHistory = $latestPriceHistory->skip(1)->first();
                return Carbon::parse($latestPriceHistory->updated_at)->format('d/m/y, H:i:s');
            }

        }
            return "";
    }
    public function getUpdatedByAttribute() {

        $vehicle = Vehicles::find($this->id);
        $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->where('int_colour', $vehicle->int_colour)
            ->where('ext_colour', $vehicle->ex_colour)
            ->first();
        if(!empty($availableColour)) {
            $updatedBy = $availableColour->user->name;
           return $updatedBy;
        }
        return " ";
    }
    public function getUpdatedAtAttribute() {

        $vehicle = Vehicles::find($this->id);
        $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->where('int_colour', $vehicle->int_colour)
            ->where('ext_colour', $vehicle->ex_colour)
            ->first();
        if(!empty($availableColour)) {
            $updatedAt = $availableColour->updated_at;
            return Carbon::parse($updatedAt)->format('d/m/Y, H:i:s');
            return  ;
        }
        return " ";
    }
    
}
