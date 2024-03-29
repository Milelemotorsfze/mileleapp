<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicleslog extends Model
{
    use HasFactory;
    protected $table = 'vehicles_log';
    public function roleName() {
        return $this->hasOne(Role::class, 'id','role');
    }
}
