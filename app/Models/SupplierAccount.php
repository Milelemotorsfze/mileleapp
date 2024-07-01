<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccount extends Model
{
    use HasFactory;
    protected $table = "supplier_account";
    public function supplier()
{
    return $this->belongsTo(Supplier::class, 'suppliers_id');
}
}
