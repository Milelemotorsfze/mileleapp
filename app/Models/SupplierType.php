<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierType extends Model
{
    use HasFactory;
    protected $table = "supplier_types";
    protected $fillable = [
        'supplier_id',
        'supplier_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
