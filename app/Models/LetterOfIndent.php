<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOfIndent extends Model
{
    use HasFactory;
    public const LOI_CATEGORY_REAL = "Real";
    public const LOI_CATEGORY_SPECIAL = "Special";
    public const LOI_SUBMISION_STATUS_NEW = "New";
    public const LOI_STATUS_SUPPLIER_APPROVED = "Supplier Approved";
    public const LOI_STATUS_SUPPLIER_REJECTED = "Supplier Rejected";
    public const LOI_STATUS_PARTIAL_APPROVED = "Partialy Approved";
    public const LOI_STATUS_APPROVED = "Approved";
    public const LOI_STATUS_REJECTED = "Rejected";
    public const LOI_STATUS_NEW = "New";
    public const LOI_STATUS_PFI_CREATED = "PFI Created";
    public const LOI_STATUS_PARTIAL_PFI_CREATED = "Partialy PFI Created";

    protected $appends = [
        'total_loi_quantity',
        'total_approved_quantity',
        'is_pfi_pending_for_loi'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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

    public function getTotalLoiQuantityAttribute() {
        $letterOfIndentItemQty = LetterOfIndentItem::where('letter_of_indent_id', $this->id)
                                    ->sum('quantity');
        if(!$letterOfIndentItemQty) {
            return 0;
        }
        return $letterOfIndentItemQty;
    }
    public function getTotalApprovedQuantityAttribute() {
        $letterOfIndentItemApprovedQty = LetterOfIndentItem::where('letter_of_indent_id', $this->id)
            ->sum('approved_quantity');
        if(!$letterOfIndentItemApprovedQty) {
            return 0;
        }
        return $letterOfIndentItemApprovedQty;
    }
    public function getIsPfiPendingForLoiAttribute() {
        $approvedloiItem = ApprovedLetterOfIndentItem::where('letter_of_indent_id', $this->id);

        if($approvedloiItem->count() > 0) {
            $isPfiPending = $approvedloiItem->whereNull('pfi_id')->get();
            if($isPfiPending->count() > 0) {
                return true;
            }
        }
         return false;
    }
}
