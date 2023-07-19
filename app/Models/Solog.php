<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solog extends Model
{
    use HasFactory;
    protected $table = 'so_log';
    public function roleName() {
        return $this->hasOne(Role::class, 'id','role');
    }
}
