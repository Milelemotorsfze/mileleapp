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
    public $appends = [
        'pfi_items'
    ];
    public function letterOfIndent()
    {
        return $this->belongsTo(LetterOfIndent::class);
    }
    public function getpfiItemsAttribute()
    {
        $approvedPfis = ApprovedLetterOfIndentItem::where('pfi_id', $this->id)
            ->get();

        return $approvedPfis;
    }
}
