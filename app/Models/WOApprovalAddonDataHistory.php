<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovalAddonDataHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_approval_addon_data_histories";
    protected $fillable = ['w_o_approvals_id','wo_addon_history_id'];
}
