<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRoles extends Model
{
    use HasFactory;
    protected $table = 'model_has_roles';
    public function user()
    {
        return $this->belongsTo(User::class, 'model_id');
    }
}
