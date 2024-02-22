<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrdersItems extends Model
{
    use HasFactory;
    protected $table = "pre_orders_items";
    public function modelLine()
{
    return $this->belongsTo(MasterModelLines::class, 'master_model_lines_id');
}

public function exColour()
{
    return $this->belongsTo(ColorCode::class, 'ex_colour');
}

public function intColour()
{
    return $this->belongsTo(ColorCode::class, 'int_colour');
}

public function country()
{
    return $this->belongsTo(Country::class, 'countries_id');
}
}
