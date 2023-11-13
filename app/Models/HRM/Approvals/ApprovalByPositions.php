<?php

namespace App\Models\HRM\Approvals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalByPositions extends Model
{
    use HasFactory;
    protected $table = "approval_by_positions";
    protected $fillable = [
        'approved_by_position',
        'approved_by_id',
        'handover_to_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
