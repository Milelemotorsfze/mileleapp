<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = [
        'calls_id',
        'date',
        'deal_value',
        'sales_notes',
        'file_path',
    ];
    public $timestamps = false;
    public function quotationdetails()
    {
        return $this->hasOne(QuotationDetail::class);
    }
}
