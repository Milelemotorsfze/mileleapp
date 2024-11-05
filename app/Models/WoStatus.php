<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoStatus extends Model
{
    use HasFactory;
    protected $table = "wo_status";
    protected $fillable = [
        'wo_id',
        'status_changed_by',
        'status',
        'comment',
        'status_changed_at',
    ];
    protected $casts = [
        'status_changed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','status_changed_by');
    }
    public function workOrder()
    {
        return $this->hasOne(workOrder::class,'id','wo_id');
    }
    public function statusBadgeClass()
    {
        return match ($this->status) {
            'On Hold' => 'badge-soft-warning',
            'Active' => 'badge-soft-success',
            'Cancelled' => 'badge-soft-danger',
            'Succeeded' => 'badge-soft-primary',
            'Partially Delivered' => 'badge-soft-info',
            default => 'badge-soft-secondary',
        };
    }
}
