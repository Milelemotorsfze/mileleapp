<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentlog extends Model
{
    use HasFactory;
    protected $table = 'documents_log';
    public function roleName() {
        return $this->hasOne(Role::class, 'id','role');
    }
}
