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
        'nature_of_deal',
    ];
    public $timestamps = false;
    public function quotationdetails()
    {
        return $this->hasOne(QuotationDetail::class);
    }
    public function so()
    {
        return $this->hasOne(So::class, 'quotation_id');
    }

    public function call()
    {
        return $this->belongsTo(Calls::class, 'calls_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
