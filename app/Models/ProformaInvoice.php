<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaInvoice extends Model
{
    use HasFactory;
    protected $table = 'proforma_invoice';
    protected $fillable = [
        'category',
        'validity',
        'final_destination',
        'incoterm',
        'place_of_delivery',
        'system_code',
        'payment_terms',
        'rep_name',
        'rep_no',
        'cb_name',
        'cb_no',
        'payment_due',  
        'net_aed',
        'net_usd',
        'accept_name',
        'accept_designtion',
        'accept_contact',
        'sale_person_id',
        'created_by',
        'calls_id',
    ];
}
