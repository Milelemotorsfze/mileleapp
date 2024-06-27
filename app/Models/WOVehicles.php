<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WOVehicles extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "w_o_vehicles";
    protected $fillable = [
        'work_order_id',
        'vehicle_id',
        'boe_number',
        'vin',
        'brand',
        'variant',
        'engine',
        'model_description',
        'model_year',
        'model_year_to_mention_on_documents',
        'steering',
        'exterior_colour',
        'interior_colour',
        'warehouse',
        'territory',
        'preferred_destination',
        'import_document_type',
        'ownership_name',
        'modification_or_jobs_to_perform_per_vin',
        'certification_per_vin',
        'special_request_or_remarks',
        'shipment',
        'created_by',
        'updated_by',
        'deleted_by',
        'deposit_received',
    ];
    protected $appends = [
        'certification_per_vin_name',
    ];
    public function getCertificationPerVinNameAttribute() {
        $certification = '';
        if($this->certification_per_vin == 'rta_without_number_plate') {
            $certification = 'RTA Without Number Plate';
        }
        else if($this->certification_per_vin == 'rta_with_number_plate') {
            $certification = 'RTA With Number Plate';
        }
        else if($this->certification_per_vin == 'certificate_of_origin') {
            $certification = 'Certificate Of Origin';
        }
        else if($this->certification_per_vin == 'certificate_of_conformity') {
            $certification = 'Certificate Of Conformity';
        }
        else if($this->certification_per_vin == 'qisj_inspection') {
            $certification = 'QISJ Inspection';
        }
        else if($this->certification_per_vin == 'eaa_inspection') {
            $certification = 'EAA Inspection';
        }
        return $certification;
    }
    public function addons()
    {
        return $this->hasMany(WOVehicleAddons::class,'w_o_vehicle_id','id');
    }
    public function addonsWithTrashed()
    {
        return $this->hasMany(WOVehicleAddons::class,'w_o_vehicle_id','id')->withTrashed();
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function UpdatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function DeletedBy()
    {
        return $this->hasOne(User::class,'id','deleted_by');
    }
}
