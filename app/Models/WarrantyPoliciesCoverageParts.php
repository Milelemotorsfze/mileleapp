<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyPoliciesCoverageParts extends Model
{
    use HasFactory;
    protected $table = "warranty_policies_coverage_parts";
    protected $fillable = [
        'policies_id',
        'status',
        'warranty_coverage_parts_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
