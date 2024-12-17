<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class LetterOfIndent extends Model
{
    use HasFactory, SoftDeletes;
    public const LOI_CATEGORY_REAL = "Original";
    public const LOI_CATEGORY_SPECIAL = "Special";
    public const LOI_CATEGORY_MANAGEMENT_REQUEST = "Management Request";
    public const LOI_CATEGORY_END_USER_CHANGED = "End User Changed";
    public const LOI_CATEGORY_QUANTITY_INFLATE = "Quantity Inflate";
    public const LOI_SUBMISION_STATUS_NEW = "New";
    public const LOI_STATUS_WAITING_FOR_APPROVAL = "Waiting for approval";
    public const LOI_STATUS_SUPPLIER_APPROVED = "Approved by Supplier";
    public const LOI_STATUS_SUPPLIER_REJECTED = "Rejected by Supplier";
    public const LOI_STATUS_PARTIAL_APPROVED = "Partialy Utilized LOI";
    public const LOI_STATUS_APPROVED = "Fully Utilized LOI";
    public const LOI_STATUS_WAITING_FOR_TTC_APPROVAL = "Waiting for TTC Approval";
    public const LOI_STATUS_TTC_APPROVED = "TTC Approved";
    public const LOI_STATUS_TTC_REJECTED = "TTC Rejected";
    public const LOI_STATUS_NEW = "New";
    public const LOI_STATUS_PFI_CREATED = "PFI Created";
    public const LOI_STATUS_PARTIAL_PFI_CREATED = "Partialy PFI Created";
    public const LOI_STATUS_EXPIRED = "Expired";

    protected $appends = [  
        'total_loi_quantity',
    ];

    
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function salesPerson()
    {
        return $this->belongsTo(User::class,'sales_person_id','id');
    }
    public function client()
    {
        return $this->belongsTo(Clients::class,'client_id','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function LOIDocuments()
    {
        return $this->hasMany(LetterOfIndentDocument::class);
    }
    public function letterOfIndentItems()
    {
        return $this->hasMany(LetterOfIndentItem::class,'letter_of_indent_id');
    }
    public function soNumbers()
    {
        return $this->hasMany(LoiSoNumber::class,'letter_of_indent_id');
    }
    public function LOITemplates()
    {
        return $this->hasMany(LoiTemplate::class,'letter_of_indent_id','id');
    }
    public function getTotalLoiQuantityAttribute() {
        $letterOfIndentItemQty = LetterOfIndentItem::where('letter_of_indent_id', $this->id)
                                    ->sum('quantity');
        if(!$letterOfIndentItemQty) {
            return 0;
        }
    
        return $letterOfIndentItemQty;
    }
   
   
   }
