<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaInvoiceItems extends Model
{
    use HasFactory;
    protected $table = 'proforma_invoice_items';
    protected $fillable = [
        'description',
        'unit_price',
        'qty',
        'total',
        'proforma_invoice_id',
    ];
}
