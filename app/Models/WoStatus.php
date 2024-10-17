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
}
