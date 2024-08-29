<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "work_orders";
    protected $fillable = [
        'type',
        'date',
        'so_number',
        'is_batch',
        'batch',
        'wo_number',

        'customer_reference_id',
        'customer_reference_type',
        'customer_name',
        'customer_email',
        'customer_company_number',
        'customer_address',

        'customer_representative_name',
        'customer_representative_email',
        'customer_representative_contact',

        'freight_agent_name',
        'freight_agent_email',
        'freight_agent_contact_number',

        'port_of_loading',
        'port_of_discharge',
        'final_destination',
        'transport_type',
        'brn_file',
        'brn',
        'container_number',
        'airline_reference_id',
        'airline',
        'airway_bill',
        'shipping_line',
        'forward_import_code',
        'trailer_number_plate',
        'transportation_company',
        'transporting_driver_contact_number',
        'airway_details',
        'transportation_company_details',

        'currency',
        'so_total_amount',
        'so_vehicle_quantity',
        'deposit_received_as',
        'amount_received',
        'balance_amount',

        'delivery_location',
        'delivery_contact_person',
        'delivery_contact_person_number',
        'delivery_date',

        // Only for Export CNF Start
        'preferred_shipping_line_of_customer',
        'bill_of_loading_details',
        'shipper',
        'consignee',
        'notify_party',
        'special_or_transit_clause_or_request',
        // Only for Export CNF End

        'signed_pfi',
        'signed_contract',
        'payment_receipts',
        'noc',
        'enduser_trade_license',
        'enduser_passport',
        'enduser_contract',
        'vehicle_handover_person_id',

        'sales_support_data_confirmation_by',
        'sales_support_data_confirmation_at',

        'finance_approval_by',
        'finance_approved_at',

        'coe_office_approval_by',
        'coe_office_approved_at',
        'coe_office_direct_approval_comments',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $appends = [
        'sales_support_data_confirmation',
        'finance_approval_status',
        'coo_approval_status',
        'total_number_of_boe',
        'vehicle_count',
        'type_name',
    ];
    public function getSalesSupportDataConfirmationAttribute() {
        $status = '';
        if($this->sales_support_data_confirmation_at == NULL) {
            $status = 'Not Confirmed';
        } else if($this->sales_support_data_confirmation_at != NULL) {
            $status = 'Confirmed';
        }
        return $status;
    }
    public function getFinanceApprovalStatusAttribute() {
        $status = '';
        $data = WOApprovals::where('work_order_id',$this->id)->where('type','finance')->orderBy('id','DESC')->first();
        if($data && $data->status == 'pending') {
            $status = 'Pending';
        } else if($data && $data->status == 'approved') {
            $status = 'Approved';
        }else if($data && $data->status == 'rejected') {
            $status = 'Rejected';
        }
        return $status;
    }
    public function getCooApprovalStatusAttribute() {
        $status = '';
        $data = WOApprovals::where('work_order_id',$this->id)->where('type','coo')->orderBy('id','DESC')->first();
        if($data && $data->status == 'pending') {
            $status = 'Pending';
        } else if($data && $data->status == 'approved') {
            $status = 'Approved';
        }else if($data && $data->status == 'rejected') {
            $status = 'Rejected';
        }
        return $status;
    }
    public function getTotalNumberOfBOEAttribute() {
        $uniqueBoeCount = '';
        $uniqueBoeCount = WOVehicles::where('work_order_id', $this->id)
        ->whereNull('deleted_at')
        ->distinct()
        ->count('boe_number');
        return $uniqueBoeCount;
    }
    public function getVehicleCountAttribute() {
        // Count the vehicles related to this work order and not deleted
        $vehicleCount = WOVehicles::where('work_order_id', $this->id)
            ->whereNull('deleted_at')
            ->count(); // This will return the number of records
    
        return $vehicleCount;
    }
    public function getTypeNameAttribute() {
        $typeName = '';
        if($this->type == 'export_exw') {
            $typeName = 'Export EXW';
        }
        else if($this->type == 'export_cnf') {
            $typeName = 'Export CNF';
        }
        else if($this->type == 'local_sale') {
            $typeName = 'Local Sale';
        }
        else if($this->type == 'lto') {
            $typeName = 'LTO';
        }
        return $typeName;
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function UpdatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function salesSupportDataConfirmationBy()
    {
        return $this->hasOne(User::class,'id','sales_support_data_confirmation_by');
    }
    public function financeApprovalBy()
    {
        return $this->hasOne(User::class,'id','finance_approval_by');
    }
    public function COOApprovalBy()
    {
        return $this->hasOne(User::class,'id','coe_office_approval_by');
    }
    public function vehicles()
    {
        return $this->hasMany(WOVehicles::class,'work_order_id','id');
    }
    public function depositAganistVin()
    {
        return $this->hasMany(WOVehicles::class,'work_order_id','id')->where('deposit_received','yes');
    }
    public function vehiclesWithTrashed()
    {
        return $this->hasMany(WOVehicles::class, 'work_order_id', 'id')->withTrashed();
    }
    public function comments()
    {
        return $this->hasMany(WOComments::class,'work_order_id','id');
    }
    public function dataHistories()
    {
        return $this->hasMany(WORecordHistory::class,'work_order_id','id');
    }
    public function financePendingApproval() {
        return $this->hasOne(WOApprovals::class)
            ->where('type', 'finance')
            ->where('status', 'pending');
    }
    public function cooPendingApproval() {
        return $this->hasOne(WOApprovals::class)
        ->where('type', 'coo')
        ->where('status', 'pending');
    }
    public function latestFinanceApproval()
    {
        return $this->hasOne(WOApprovals::class)
            ->where('type', 'finance')
            ->whereIn('status', ['approved', 'rejected'])
            ->latestOfMany('action_at');
    }
    public function latestCooPendingApproval()
    {
        return $this->hasOne(WOApprovals::class)
            ->where('type', 'coo')
            ->whereIn('status', ['approved', 'rejected'])
            ->latestOfMany('action_at');
    }
}
