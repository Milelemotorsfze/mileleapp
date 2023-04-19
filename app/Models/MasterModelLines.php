<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModelLines extends Model
{
    use HasFactory;
    public function Variant()
    {
        return $this->hasOne(Varaint::class,'master_model_lines_id');
    }
}
