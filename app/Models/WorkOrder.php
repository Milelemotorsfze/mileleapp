<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "work_orders";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'type',
        'date',
        'so_number',
        'temporary_exit',
        'delivery_advise',
        'showroom_transfer',
        'cross_trade',
        'lto',
        'is_batch',
        'batch',
        'wo_number',
        'sales_person_id',

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

        'has_claim',

        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $appends = [
        'status',
        'sales_support_data_confirmation',
        'can_revert_confirmation',
        'finance_approval_status',
        'can_show_fin_approval',
        'can_show_coo_approval',
        'coo_approval_status',
        'docs_status',
        'total_number_of_boe',
        'vehicle_count',
        'type_name',
        'vehicles_no_modifications_count',
        'vehicles_initiated_count',
        'vehicles_not_initiated_count',
        'vehicles_completed_count',
        'vehicles_modification_summary',
        'is_modification_initial_stage',
        'pdi_scheduled_count',
        'pdi_not_initiated_count',
        'pdi_completed_count',
        'pdi_summary',
        'is_pdi_initial_stage',
        'delivery_ready_count',
        'delivery_on_hold_count',
        'delivery_delivered_count',
        'delivery_delivered_with_docs_hold_count',
        'delivery_summary',
        'is_delivery_initial_stage',
    ];
    public function getStatusAttribute() {
        $status = '';
        
        // Fetch the most recent record for the current work order
        $data = WoStatus::where('wo_id', $this->id)
            ->orderBy('status_changed_at', 'DESC')
            ->first();
        
        // If data exists, update the status to the latest one
        if ($data) {
            $status = $data->status;
        }
    
        return $status;
    }
    public function getSalesSupportDataConfirmationAttribute() {
        $status = '';
        if($this->sales_support_data_confirmation_at == NULL) {
            $status = 'Not Confirmed';
        } else if($this->sales_support_data_confirmation_at != NULL) {
            $status = 'Confirmed';
        }
        return $status;
    }
    public function getCanRevertConfirmationAttribute() {
        $canRevert = 'yes';
        if(($this->sales_support_data_confirmation == 'Confirmed' && $this->finance_approval_status == 'Approved' && $this->coo_approval_status == 'Approved')
            && (($this->docs_status != 'Blank' && $this->docs_status != 'Not Initiated') || $this->is_modification_initial_stage == 'no' 
                || $this->is_pdi_initial_stage == 'no' || $this->is_delivery_initial_stage == 'no')) {
                $canRevert = 'no';
            }
        return $canRevert;
    }
    public function getFinanceApprovalStatusAttribute()
    {
        $data = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'finance')
                            ->orderBy('updated_at', 'DESC')
                            ->first();

        if (!$data) {
            return '';
        }

        return match ($data->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => '',
        };
    }

    public function getCanShowFinApprovalAttribute()
    {
        $canShowFinApproval = 'yes';
        $current = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'finance')
                            ->orderBy('id', 'DESC')
                            ->first();
        $first = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'finance')
                            ->orderBy('id', 'ASC')
                            ->first();

        if (isset($current) && isset($first) && $current->id == $first->id && $this->sales_support_data_confirmation_at == '' && $current->status == 'pending') {
            $canShowFinApproval = 'no';
        }

        return $canShowFinApproval;
    }
    public function getCooApprovalStatusAttribute()
    {
        $data = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'coo')
                            ->orderBy('updated_at', 'DESC')
                            ->first();
        if (!$data || $this->can_show_coo_approval !== 'yes') {
            return '';
        }

        return match ($data->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => '',
        };
    }

    // Determine if COO approval should be shown
    public function getCanShowCOOApprovalAttribute()
    {
        $canShowCOOApproval = 'yes';
        $current = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'coo')
                            ->orderBy('id', 'DESC')
                            ->first();
        $first = WOApprovals::where('work_order_id', $this->id)
                            ->where('type', 'coo')
                            ->orderBy('id', 'ASC')
                            ->first();

        if (isset($current) && isset($first) && $current->id == $first->id 
            && ($this->sales_support_data_confirmation_at == '' || $this->finance_approval_status != 'Approved')
            && $current->status == 'pending') {
            $canShowCOOApproval = 'no';
        }

        return $canShowCOOApproval;
    }
    public function getDocsStatusAttribute()
    {
        $status = 'Blank';  // Default status if conditions are not met

        if ($this->sales_support_data_confirmation_at && 
            $this->finance_approval_status === 'Approved' && 
            $this->coo_approval_status === 'Approved') {
            $status = 'Not Initiated';
        }

        // Fetch the most recent record for the current work order
        $data = WoDocsStatus::where('wo_id', $this->id)
                            ->orderBy('doc_status_changed_at', 'DESC')
                            ->first();

        // If data exists, update the status to the latest one
        if ($data) {
            $status = $data->is_docs_ready;
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
    // Attribute to get the count of "No Modifications" vehicles
    public function getVehiclesNoModificationsCountAttribute()
    {
        return $this->vehicles->where('modification_status', 'No Modifications')->count();
    }
    // Attribute to get the count of "Initiated" vehicles
    public function getVehiclesInitiatedCountAttribute()
    {
        return $this->vehicles->where('modification_status', 'Initiated')->count();
    }
    // Attribute to get the count of "Not Initiated" vehicles
    public function getVehiclesNotInitiatedCountAttribute()
    {
        return $this->vehicles->where('modification_status', 'Not Initiated')->count();
    }

    // Attribute to get the count of "Completed" vehicles
    public function getVehiclesCompletedCountAttribute()
    {
        return $this->vehicles->where('modification_status', 'Completed')->count();
    }
    public function getVehiclesModificationSummaryAttribute()
    {
        $noModificationsCount = $this->vehicles_no_modifications_count;
        $completedCount = $this->vehicles_completed_count;
        $initiatedCount = $this->vehicles_initiated_count;
        $notInitiatedCount = $this->vehicles_not_initiated_count;
    
        // Get the total number of vehicles related to the work order
        $totalVehiclesCount = $this->vehicles()->count();
    
        // Special case: if all vehicles have no modifications
        if ($noModificationsCount == $totalVehiclesCount) {
            return 'NO MODIFICATIONS';
        }
    
        // Special case: if all vehicles are either completed or have no modifications
        if (($completedCount + $noModificationsCount) == $totalVehiclesCount) {
            return 'COMPLETED';
        }
    
        // Check for single status
        if ($completedCount > 0 && !$initiatedCount && !$notInitiatedCount && !$noModificationsCount) {
            return 'COMPLETED';
        }
        if ($noModificationsCount > 0 && !$completedCount && !$initiatedCount && !$notInitiatedCount) {
            return 'NO MODIFICATIONS';
        }
        if ($initiatedCount > 0 && !$completedCount && !$notInitiatedCount && !$noModificationsCount) {
            return 'INITIATED';
        }
        if ($notInitiatedCount > 0 && !$completedCount && !$initiatedCount && !$noModificationsCount) {
            return 'NOT INITIATED';
        }
    
        // If no specific status applies, return the combined summary
        $parts = [];
        if ($completedCount > 0) {
            $parts[] = "{$completedCount} COMPLETED";
        }
        if ($initiatedCount > 0) {
            $parts[] = "{$initiatedCount} INITIATED";
        }
        if ($notInitiatedCount > 0) {
            $parts[] = "{$notInitiatedCount} NOT INITIATED";
        }
        if ($noModificationsCount > 0) {
            $parts[] = "{$noModificationsCount} NO MODIFICATIONS";
        }
    
        // Return the concatenated summary
        return implode(' & ', $parts);
    }
    
    public function getIsModificationInitialStageAttribute() {
        $isModificationInitialStage = 'yes';
        if($this->vehicles_completed_count > 0 || $this->vehicles_initiated_count > 0) {
            $isModificationInitialStage = 'no';
        }
        return $isModificationInitialStage;
    }
    // Attribute to get the count of pdi "Scheduled" vehicles
    public function getPDIScheduledCountAttribute()
    {
        return $this->vehicles->where('pdi_status', 'Scheduled')->count();
    }

    // Attribute to get the count of pdi "Not Initiated" vehicles
    public function getPDINotInitiatedCountAttribute()
    {
        return $this->vehicles->where('pdi_status', 'Not Initiated')->count();
    }

    // Attribute to get the count of pdi "Completed" vehicles
    public function getPDICompletedCountAttribute()
    {
        return $this->vehicles->where('pdi_status', 'Completed')->count();
    }

    public function getPDISummaryAttribute()
    {
        $completedCount = $this->pdi_completed_count;
        $scheduledCount = $this->pdi_scheduled_count;
        $notInitiatedCount = $this->pdi_not_initiated_count;

        if ($completedCount > 0 && $scheduledCount == 0 && $notInitiatedCount == 0) {
            return 'COMPLETED';
        }

        if ($completedCount > 0 && $scheduledCount > 0 && $notInitiatedCount == 0) {
            return "{$completedCount} COMPLETED & {$scheduledCount} SCHEDULED";
        }

        if ($completedCount > 0 && $scheduledCount == 0 && $notInitiatedCount > 0) {
            return "{$completedCount} COMPLETED & {$notInitiatedCount} NOT INITIATED";
        }

        if ($completedCount > 0 && $scheduledCount > 0 && $notInitiatedCount > 0) {
            return "{$completedCount} COMPLETED & {$scheduledCount} SCHEDULED & {$notInitiatedCount} NOT INITIATED";
        }

        if ($scheduledCount > 0 && $completedCount == 0 && $notInitiatedCount == 0) {
            return 'SCHEDULED';
        }

        if ($scheduledCount > 0 && $notInitiatedCount > 0 && $completedCount == 0) {
            return "{$scheduledCount} SCHEDULED & {$notInitiatedCount} NOT INITIATED";
        }

        if ($notInitiatedCount > 0 && $scheduledCount == 0 && $completedCount == 0) {
            return 'NOT INITIATED';
        }

        return 'NO DATA AVAILABLE';
    }
    public function getIsPDIInitialStageAttribute() {
        $isPDIInitialStage = 'yes';
        if($this->pdi_scheduled_count > 0 || $this->pdi_completed_count > 0) {
            $isPDIInitialStage = 'no';
        }
        return $isPDIInitialStage;
    }
    // Attribute to get the count of delivery "Ready" vehicles
    public function getDeliveryReadyCountAttribute()
    {
        return $this->vehicles->where('delivery_status', 'Ready')->count();
    }

    // Attribute to get the count of delivery "On Hold" vehicles
    public function getDeliveryOnHoldCountAttribute()
    {
        return $this->vehicles->where('delivery_status', 'On Hold')->count();
    }

    // Attribute to get the count of delivery "Delivered" vehicles
    public function getDeliveryDeliveredCountAttribute()
    {
        return $this->vehicles->where('delivery_status', 'Delivered')->count();
    }
    // Attribute to get the count of delivery "Delivered With Docs Hold" vehicles
    public function getDeliveryDeliveredWithDocsHoldCountAttribute()
    {
        return $this->vehicles->where('delivery_status', 'Delivered With Docs Hold')->count();
    }
    public function getDeliverySummaryAttribute()
    {
        $deliveredCount = $this->delivery_delivered_count;
        $readyCount = $this->delivery_ready_count;
        $onHoldCount = $this->delivery_on_hold_count;
        $deliveredWithDocsHoldCount = $this->delivery_delivered_with_docs_hold_count;

        if ($deliveredCount > 0 && $readyCount == 0 && $onHoldCount == 0 && $deliveredWithDocsHoldCount == 0) {
            return 'DELIVERED WITH DOCUMENTS';
        }

        if ($deliveredCount > 0 && $readyCount > 0 && $onHoldCount == 0 && $deliveredWithDocsHoldCount == 0) {
            return "{$deliveredCount} DELIVERED & {$readyCount} READY";
        }

        if ($deliveredCount > 0 && $readyCount == 0 && $onHoldCount > 0 && $deliveredWithDocsHoldCount == 0) {
            return "{$deliveredCount} DELIVERED & {$onHoldCount} ON HOLD";
        }

        if ($deliveredCount > 0 && $readyCount > 0 && $onHoldCount > 0 && $deliveredWithDocsHoldCount == 0) {
            return "{$deliveredCount} DELIVERED & {$readyCount} READY & {$onHoldCount} ON HOLD";
        }

        if ($readyCount > 0 && $deliveredCount == 0 && $onHoldCount == 0 && $deliveredWithDocsHoldCount == 0) {
            return 'READY';
        }

        if ($readyCount > 0 && $onHoldCount > 0 && $deliveredCount == 0 && $deliveredWithDocsHoldCount == 0) {
            return "{$readyCount} READY & {$onHoldCount} ON HOLD";
        }

        if ($onHoldCount > 0 && $readyCount == 0 && $deliveredCount == 0 && $deliveredWithDocsHoldCount == 0) {
            return 'ON HOLD';
        }

        if ($deliveredWithDocsHoldCount > 0 && $deliveredCount == 0 && $readyCount == 0 && $onHoldCount == 0) {
            return 'DELIVERED/DOCUMENTS HOLD';
        }

        if ($deliveredCount > 0 && $deliveredWithDocsHoldCount > 0 && $readyCount == 0 && $onHoldCount == 0) {
            return "{$deliveredCount} DELIVERED & {$deliveredWithDocsHoldCount} DELIVERED/DOCUMENTS HOLD";
        }

        if ($deliveredCount > 0 && $deliveredWithDocsHoldCount > 0 && $readyCount > 0 && $onHoldCount == 0) {
            return "{$deliveredCount} DELIVERED & {$deliveredWithDocsHoldCount} DELIVERED/DOCUMENTS HOLD & {$readyCount} READY";
        }

        if ($deliveredCount > 0 && $deliveredWithDocsHoldCount > 0 && $readyCount > 0 && $onHoldCount > 0) {
            return "{$deliveredCount} DELIVERED & {$deliveredWithDocsHoldCount} DELIVERED/DOCUMENTS HOLD & {$readyCount} READY & {$onHoldCount} ON HOLD";
        }

        return 'NO DATA AVAILABLE';
    }
    public function getIsDeliveryInitialStageAttribute() {
        $isDeliveryInitialStage = 'yes';
        if($this->delivery_ready_count > 0 || $this->delivery_delivered_count > 0 || $this->delivery_delivered_with_docs_hold_count > 0) {
            $isDeliveryInitialStage = 'no';
        }
        return $isDeliveryInitialStage;
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function salesPerson()
    {
        return $this->hasOne(User::class,'id','sales_person_id');
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
    public function boe()
    {
        return $this->hasMany(WOBOE::class,'wo_id','id');
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
    public function latestFinance()
    {
        return $this->hasOne(WOApprovals::class)
                    ->where('type', 'finance')  // Filter for finance type
                    ->orderBy('updated_at', 'DESC');  // Get the latest based on updated_at
    }
    public function latestCOO()
    {
        return $this->hasOne(WOApprovals::class)
                    ->where('type', 'coo')  // Filter for coo type
                    ->orderBy('updated_at', 'DESC');  // Get the latest based on updated_at
    }
    public function latestCooPendingApproval()
    {
        return $this->hasOne(WOApprovals::class)
            ->where('type', 'coo')
            ->whereIn('status', ['approved', 'rejected'])
            ->latestOfMany('action_at');
    }
    public function latestDocsStatus()
    {
        return $this->hasOne(WoDocsStatus::class, 'wo_id')
                    ->latestOfMany('doc_status_changed_at');
    }
    public function latestDocs()
    {
        return $this->hasOne(WoDocsStatus::class, 'wo_id')  // Specify the correct foreign key here
                    ->orderBy('doc_status_changed_at', 'DESC');  // Get the latest based on doc_status_changed_at
    }
    public function latestStatus()
    {
        return $this->hasOne(WoStatus::class, 'wo_id')
            ->latestOfMany('status_changed_at'); 
    }
    public function confirmationStatus()
    {
        return $this->sales_support_data_confirmation_at ? 'Confirmed' : 'Not Confirmed';
    }
    public function latestStatusBadgeClass()
    {
        return match ($this->latestStatus->status ?? '') {
            'On Hold' => 'badge-soft-warning',
            'Active' => 'badge-soft-success',
            'Cancelled' => 'badge-soft-danger',
            'Succeeded' => 'badge-soft-primary',
            'Partially Delivered' => 'badge-soft-info',
            default => 'badge-soft-secondary',
        };
    }
    public function financeApprovalBadgeClass()
    {
        return match ($this->finance_approval_status) {
            'Pending' => 'badge-soft-info',
            'Approved' => 'badge-soft-success',
            'Rejected' => 'badge-soft-danger',
            default => 'badge-soft-secondary',
        };
    }
    public function cooApprovalBadgeClass()
    {
        return match ($this->coo_approval_status) {
            'Pending' => 'badge-soft-info',
            'Approved' => 'badge-soft-success',
            'Rejected' => 'badge-soft-danger',
            default => 'badge-soft-secondary',
        };
    }
    public function getBadgeClass($fieldValue)
    {
        return match ($fieldValue) {
            'In Progress' => 'badge-soft-info',
            'Ready' => 'badge-soft-success',
            'Not Initiated' => 'badge-soft-danger',
            'INITIATED' => 'badge-soft-info',
            'NO MODIFICATIONS' => 'badge-soft-warning',
            'NOT INITIATED' => 'badge-soft-danger',
            'COMPLETED' => 'badge-soft-success',
            'READY' => 'badge-soft-info',
            'ON HOLD' => 'badge-soft-danger',
            'DELIVERED WITH DOCS HOLD' => 'badge-soft-warning',
            'DELIVERED' => 'badge-soft-success',
            default => 'badge-soft-dark',
        };
    }
    public function getFormBadgeClass($status, $type)
    {
        return match ($type) {
            'status' => match ($status) {
                'On Hold' => 'badge-soft-warning',
                'Active' => 'badge-soft-success',
                'Cancelled' => 'badge-soft-danger',
                'Succeeded' => 'badge-soft-primary',
                'Partially Delivered' => 'badge-soft-info',
                default => 'badge-soft-secondary',
            },
            'confirmation' => match ($status) {
                'Confirmed' => 'badge-soft-success',
                'Not Confirmed' => 'badge-soft-danger',
                default => 'badge-soft-secondary',
            },
            'approval' => match ($status) {
                'Pending' => 'badge-soft-info',
                'Approved' => 'badge-soft-success',
                'Rejected' => 'badge-soft-danger',
                default => 'badge-soft-secondary',
            },
            'docs' => match ($status) {
                'In Progress' => 'badge-soft-info',
                'Ready' => 'badge-soft-success',
                'Not Initiated' => 'badge-soft-danger',
                default => 'badge-soft-secondary',
            },
            'modification' => match ($status) {
                'INITIATED' => 'badge-soft-info',
                'NOT INITIATED' => 'badge-soft-danger',
                'NO MODIFICATIONS' => 'badge-soft-warning',
                'COMPLETED' => 'badge-soft-success',
                default => 'badge-soft-dark',
            },
            'delivery' => match ($status) {
                'READY' => 'badge-soft-info',
                'ON HOLD' => 'badge-soft-danger',
                'DELIVERED' => 'badge-soft-success',
                'DELIVERED WITH DOCS HOLD' => 'badge-soft-warning',
                default => 'badge-soft-dark',
            },
            default => 'badge-soft-secondary',
        };
    }
    public function formatDate($date)
    {
        return $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '';
    }

    public function getTransportField($field)
    {
  
        return match ($this->transport_type) {
            'air' => match ($field) {
                'name' => $this->airline,
                'id' => $this->airway_bill,
                'details' => $this->airway_details,
            },
            'sea' => match ($field) {
                'name' => $this->shipping_line,
                'id' => $this->container_number,
                'details' => $this->forward_import_code,
                'additional' => $this->brn,
            },
            'road' => match ($field) {
                'name' => $this->trailer_number_plate,
                'id' => $this->transportation_company,
                'details' => $this->transporting_driver_contact_number,
                'additional' => $this->transportation_company_details,
            },
            default => '',
        };
    }
}
