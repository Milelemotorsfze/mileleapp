<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'nationality',
        'iso_3166_code',
        'is_african_country',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

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
