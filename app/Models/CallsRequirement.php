<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallsRequirement extends Model
{
    use HasFactory;
    protected $table = 'calls_requirement';
    protected $fillable = [
        'model_line_id',
        'lead_id',
        'created_at',
        'trim',
        'variant',
        'qty',
        'asking_price',
        'offer_price',
        'countries_id',
    ];
    public $timestamps = false;
    public function masterModelLine()
    {
        return $this->belongsTo(MasterModelLines::class,'model_line_id','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'countries_id','id');
    }
}
