<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartAddon extends Model
{
    use HasFactory;
    protected $table = 'cart_addon';
    protected $fillable = ['cart_id', 'addon_id', 'created_by', /* other fillable attributes */];
}
