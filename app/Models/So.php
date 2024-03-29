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
        'payment_percentage',
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
}
