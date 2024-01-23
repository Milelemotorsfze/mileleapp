<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInventoryHistory extends Model
{
    use HasFactory;
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class,'master_model_id','id');
    }
}
