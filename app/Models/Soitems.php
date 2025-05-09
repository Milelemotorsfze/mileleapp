<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soitems extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'so_id',            // Sales Order ID
        'quotation_items_id', // Quotation Items ID
        'vehicles_id',      // Vehicles ID
        // Add any other attributes that you want to allow for mass assignment
    ];
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicles_id');
    }
}
