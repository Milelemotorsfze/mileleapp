<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    public  $appends = [
        'similar_vehicles_with_price',
        'similar_vehicles_without_price'
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
}
