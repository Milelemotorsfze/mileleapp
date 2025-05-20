<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    use HasFactory;
    protected $table = 'grn';
    protected $fillable = [
        'date',
        'grn_number',
    ];
    public $timestamps = false;

    public function vehicles()
    {
        return $this->hasMany(Vehicles::class, 'grn_id');
    }
}
