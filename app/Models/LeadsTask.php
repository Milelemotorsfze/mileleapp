<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadsTask extends Model
{
    use HasFactory;
    protected $table = 'leads_task';
    protected $fillable = ['lead_id', 'assigned_by', 'task_message', 'status'];
    public function assigner()
    {
        return $this->belongsTo(User::class,'assigned_by','id');
    }
}
