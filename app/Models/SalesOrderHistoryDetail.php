<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderHistoryDetail extends Model
{
    use HasFactory;
     protected $fillable = ['sales_order_history_id','type','so_variant_id','so_item_id','field_name','old_value','new_value', 'created_at', 'updated_at'];
}
