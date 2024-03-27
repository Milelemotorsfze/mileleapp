<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoiCountryCriteria extends Model
{
    use HasFactory;
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';


    const YES = 1;
    const NO = 2;
    const NONE = NULL;
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function modelLine()
    {
        return $this->belongsTo(MasterModelLines::class,'master_model_line_id','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
