<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public function neighbors()
    {
        return $this->belongsToMany(Country::class, 'neighboring_countries', 'country_id', 'neighbor_country_id');
    } 
    public function ports()
    {
        return $this->hasMany(MasterShippingPorts::class, 'country_id');
    } 
    public function clientCountries()
    {
        return $this->hasMany(ClientCountry::class,'country_id');
    }     
}
