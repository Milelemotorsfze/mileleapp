<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovals extends Model
{
    use HasFactory;
    protected $table = "w_o_approvals";
    protected $fillable = ['work_order_id','type', 'status', 'comments','user_id','action_at'];
}
