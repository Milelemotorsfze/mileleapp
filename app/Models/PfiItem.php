<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PfiItem extends Model
{
    use HasFactory, softDeletes;

    public function pfi()
    {
        return $this->belongsTo(PFI::class,'pfi_id','id');
    }
    public function letterOfIndentItem()
    {
        return $this->belongsTo(LetterOfIndentItem::class,'loi_item_id','id');
    }
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class,'master_model_id','id');
    }
    
}
