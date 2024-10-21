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
        'deleted_comment_id',
        'wo_boe_id',
    ];
    protected $appends = [
        'certification_per_vin_name',
        'modification_status',
        'pdi_status',
        'delivery_status',
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
    public function getModificationStatusAttribute() {
        // if($this->sales_support_data_confirmation_at != '' && $this->finance_approval_status == 'Approved' && $this->coo_approval_status == 'Approved') {
            $status = 'Not Initiated';
        // }
        // else {
        //     $status = 'Blank';
        // }
        // Get the latest modification status from the database
        $data = WOVehicleStatus::where('w_o_vehicle_id', $this->id)
                                ->orderBy('created_at', 'DESC')
                                ->first();
    
        // Check if no modification jobs and no addons, set status to 'No Modifications'
        if ($this->modification_or_jobs_to_perform_per_vin == null && $this->addons()->count() == 0) {
            $status = 'No Modifications';
        }
        // Otherwise, if modification status data exists, use the latest status
        elseif ($data) {
            $status = $data->status;
        }
    
        return $status;
    } 
    public function getPDIStatusAttribute() {
        // Set default status to 'Not Initiated'
        $status = 'Not Initiated';
    
        // Get the latest pdi status from the database
        $data = WOVehiclePDIStatus::where('w_o_vehicle_id', $this->id)
                                ->orderBy('created_at', 'DESC')
                                ->first();
    
        //if pdi status data exists, use the latest status
        if ($data) {
            $status = $data->status;
        }
    
        return $status;
    }  
    public function getDeliveryStatusAttribute() {
        // Set default status to 'On Hold'
        $status = 'On Hold';
    
        // Get the latest pdi status from the database
        $data = WOVehicleDeliveryStatus::where('w_o_vehicle_id', $this->id)
                                ->orderBy('created_at', 'DESC')
                                ->first();
    
        //if pdi status data exists, use the latest status
        if ($data) {
            $status = $data->status;
        }
    
        return $status;
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
    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class,'id','work_order_id');
    }
    public function commentVehicle()
    {
        return $this->hasMany(WOVehicleRecordHistory::class, 'comment_id', 'comment_id');
    }
    public function commentUpdatedVehicle()
    {
        return $this->hasMany(WOVehicleRecordHistory::class, 'w_o_vehicle_id', 'id');
    }
    public function latestModificationStatus()
    {
        return $this->hasOne(WOVehicleStatus::class, 'w_o_vehicle_id') // Explicitly define the foreign key here
            ->latestOfMany('created_at');  // Sort by the date field
    }
    public function latestPdiStatus()
    {
        return $this->hasOne(WOVehiclePDIStatus::class, 'w_o_vehicle_id') // Explicitly define the foreign key here
            ->latestOfMany('created_at');  // Sort by the date field
    }
    public function latestDeliveryStatus()
    {
        return $this->hasOne(WOVehicleDeliveryStatus::class, 'w_o_vehicle_id') // Explicitly define the foreign key here
            ->latestOfMany('created_at');  // Sort by the date field
    }
}
