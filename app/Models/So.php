<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class So extends Model
{
    use HasFactory;
    protected $table = 'so';
    protected $fillable = [
        'sales_person_id',
        'so_date',
        'so_number',
        'quotation_id',
        'logistics_detail_id',
        'notes',
        'created_at',
        'updated_at',
        'sales_type',
        'total',
        'receiving',
        'paidinso',
        'paidinperforma',
        'created_by',
        'updated_by',
    ];
    public $timestamps = false;
    public function vehicles()
    {
        return $this->hasMany(Vehicles::class);
    }
    public function salesperson()
    {
        return $this->belongsTo(User::class,'sales_person_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    public function call()
    {
        return $this->hasOneThrough(Calls::class, Quotation::class, 'id', 'id', 'quotation_id', 'calls_id');
    }
}
