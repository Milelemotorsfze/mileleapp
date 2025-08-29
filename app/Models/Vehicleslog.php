<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicleslog extends Model
{
    use HasFactory;
    protected $table = 'vehicles_log';
    
    protected $fillable = [
        'vehicles_id',
        'field',
        'old_value',
        'new_value',
        'status',
        'created_by',
        'time',
        'date'
    ];
    
    public function roleName() {
        return $this->hasOne(Role::class, 'id','role');
    }
}
