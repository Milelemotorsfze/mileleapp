<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    public  $appends = [
        'price'
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
    public function getPriceAttribute() {
        $availableColour = AvailableColour::where('varaint_id', $this->varaints_id)
            ->where('int_colour', $this->int_colour )
            ->where('ext_colour', $this->ex_colour)
            ->first();
        if($availableColour) {
            return $availableColour->price;
        }
        return 0;
    }
}
