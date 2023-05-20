<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOfIndentItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $appends = [
        'steering'
    ];
    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class);
    }
    public function Variant()
    {
        return $this->belongsTo(Varaint::class,'variant_name','name');
    }

    public function getSteeringAttribute()
    {
       $mastermodel = MasterModel::where('model',$this->model)
                    ->where('sfx',$this->sfx)
                    ->first();
       if ($mastermodel)
       {
           return $mastermodel->steering;
       }
       return null;
    }
}
