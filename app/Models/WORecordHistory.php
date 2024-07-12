<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WORecordHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_record_histories";
    protected $fillable = ['type','work_order_id','user_id','field_name', 'old_value', 'new_value','changed_at','comment_id'];
    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];
    protected $appends = [
        'field'
    ];
    
    public function getFieldAttribute() {
        $fieldMapping = [
            'airline' => 'Airline',
            'airway_bill' => 'Airway Bill',
            'airway_details' => 'Airway Details',
            'amount_received' => 'Amount Received',
            'balance_amount' => 'Balance Amount',
            'batch' => 'Batch',
            'brn' => 'BRN',
            'brn_file' => 'BRN File',
            'coe_office_approval_by' => 'COO Office Approval By',
            'coe_office_approved_at' => 'COO Office Approved At',
            'container_number' => 'Container Number',
            'currency' => 'Currency',
            'customer_address' => 'Customer Address',
            'customer_company_number' => 'Customer Company Number', // either customer_company_number OR customer_company_number.full needed
            'customer_company_number.full' => 'Customer Company Number',
            'customer_email' => 'Customer Email',
            'customer_name' => 'Customer Name',
            'customer_reference_id' => 'Customer Reference ID', // check for its need
            'customer_representative_contact' => 'Customer Representative Contact',// either customer_representative_contact OR customer_representative_contact.full needed
            'customer_representative_contact.full' => 'Customer Representative Contact',
            'customer_representative_email' => 'Customer Representative Email',
            'customer_representative_name' => 'Customer Representative Name',
            // date is not coming in history table
            'delivery_contact_person' => 'Delivery Contact Person',
            'delivery_date' => 'Delivery Date',
            'delivery_location' => 'Delivery Location',
            'deposit_received_as' => 'Deposit Received As',
            'enduser_contract' => 'Enduser Contract',
            'enduser_passport' => 'Enduser Passport',
            'enduser_trade_license' => 'Enduser Trade License',
            'existing_customer_name' => 'Existing Customer Name',
            'final_destination' => 'Final Destination',
            'finance_approved_at' =>'Finance Approved At',
            'finance_approval_by' => 'Finance Approval By',
            'forward_import_code' => 'Forward Import Code',
            'freight_agent_contact_number' => 'Freight Agent Contact Number',
            'freight_agent_contact_number.full' => 'Freight Agent Contact Number',
            'freight_agent_email' => 'Freight Agent Email',
            'freight_agent_name' => 'Freight Agent Name',
            'noc' => 'NOC',
            'payment_receipts' => 'Payment Receipts',
            'port_of_discharge' => 'Port Of Discharge',
            'port_of_loading' => 'Port Of Loading',
            'sales_support_data_confirmation_at' => 'Sales Support Data Confirmation At',
            'sales_support_data_confirmation_by' => 'Sales Support Data Confirmation By',
            'shipment' => 'Shipment',
            'shipping_line' => 'Shipping Line',
            'signed_contract' => 'Signed Contract',
            'signed_pfi' => 'Signed PFI',
            'so_number' => 'SO Number',
            'so_total_amount' => 'SO Total Amount',
            'so_vehicle_quantity' => 'SO Vehicle Quantity',
            'trailer_number_plate' => 'Trailer Number Plate',
            'transport_type' => 'Transport Type',
            'transportation_company' => 'Transportation Company',
            'transportation_company_details' => 'Transportation Company Details',
            'transporting_driver_contact_number' => 'Transporting Driver Contact Number', // either transporting_driver_contact_number OR transporting_driver_contact_number.full needed
            'transporting_driver_contact_number.full' => 'Transporting Driver Contact Number',
            'vehicle_handover_person_id' => 'Vehicle Handover Person ID',
            'vin_multiple' => 'VIN Multiple', // check the need of this field
            'wo_number' => 'WO Number',
        ];
    
        return $fieldMapping[$this->field_name] ?? '';
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    
}
