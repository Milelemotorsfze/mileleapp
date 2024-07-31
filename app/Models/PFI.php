<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PFI extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pfi";
    public const PFI_STATUS_NEW = 'New';
    public const PFI_PAYMENT_STATUS_PAID = 'PAID';
    public const PFI_PAYMENT_STATUS_UNPAID = 'UNPAID';
    public const PFI_PAYMENT_STATUS_PARTIALY_PAID = 'PARTIALY PAID';
    public const PFI_PAYMENT_STATUS_CANCELLED = 'CANCELLED';

    public $appends = [
        'pfi_items'
    ];
    public function letterOfIndent()
    {
        return $this->belongsTo(LetterOfIndent::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function pfiItems()
    {
        return $this->hasMany(PfiItem::class,'pfi_id','id');
    }
    public function getpfiItemsAttribute()
    {
        $approvedPfis = ApprovedLetterOfIndentItem::where('pfi_id', $this->id)
            ->get();

        return $approvedPfis;
    }
}
