<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "addons";
    protected $fillable = [
        'addon_type',
        'name',
        'kit_year',
        'kit_km',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function createdUser() {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedUser() {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function addondescription() {
        return $this->belongsTo(AddonDescription::class,'id','addon_id');
    }
}
