<?php

namespace App\Models;
use App\Models\CallsRequirement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;
    protected $table = 'calls';
    protected $fillable = [
        'name',
        'email',
        'sales_person',
        'remarks',
        'phone',
        'source',
        'status',
        'language',
        'location',
        'created_by',
        'type',
        'region',
        'custom_brand_model',
        'created_at',
        'customer_coming_type',
    ];
    public $timestamps = false;
    public function callRequirement()
    {
        return $this->hasMany(CallsRequirement::class,'lead_id','id');
    }
}
