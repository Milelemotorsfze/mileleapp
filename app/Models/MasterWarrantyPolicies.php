<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterWarrantyPolicies extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "master_warranty_policies";
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function warrantyPolicyCoverageParts()
    {
        return $this->hasMany(WarrantyPoliciesCoverageParts::class,'policies_id');
    }

}
