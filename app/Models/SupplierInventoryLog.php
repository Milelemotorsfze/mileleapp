<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInventoryLog extends Model
{
    use HasFactory;
    public function updatedBy()
    {
        return $this->hasOne(User::class,'id', 'updated_by');

    }
}
