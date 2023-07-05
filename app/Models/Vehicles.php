<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    public  $appends = [
        'similar_vehicles_with_price',
        'similar_vehicles_without_price',
        'old_price',
        'old_price_dated',
        'updated_by',
        'price_status'
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

    public function getSimilarVehiclesWithPriceAttribute()
    {
        $vehicles = Vehicles::whereNotNull('price')
            ->where('varaints_id', $this->varaints_id)
            ->groupBy('int_colour', 'ex_colour')
            ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
            ->get();

        return $vehicles;
    }
    public function getSimilarVehiclesWithoutPriceAttribute() {
        $vehicles =  Vehicles::whereNull('price')
            ->where('varaints_id', $this->varaints_id)
            ->groupBy('int_colour','ex_colour')
            ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
            ->get();

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

        $vehicle = Vehicles::find($this->id);
        $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->where('int_colour', $vehicle->int_colour)
            ->where('ext_colour', $vehicle->ex_colour)
            ->first();
        if(!empty($availableColour)) {
            $latestPriceHistory = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
                ->orderBy('id','DESC')
                ->first();
            if(!empty($latestPriceHistory)) {
                return $latestPriceHistory->status;
            }
            return "";
        }
        return "";

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
            if ($latestPriceHistory->count() > 1) {
                $latestPriceHistory = $latestPriceHistory->skip(1)->first();
                return Carbon::parse($latestPriceHistory->updated_at)->format('d M Y');
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
            return $updatedAt;
        }
        return " ";
    }
}
