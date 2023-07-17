<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyPoliciesCoverageParts extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = "warranty_policies_coverage_parts";
    protected $fillable = [
        'policies_id',
        'status',
        'warranty_coverage_parts_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function warrantyCoveragePart() {
        return $this->belongsTo(MasterWarrantyCoverageParts::class,'parts_id');
    }
}
