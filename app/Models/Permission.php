<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Permission extends Model
{
    use HasFactory;
    public function module() {
        return $this->belongsTo(Modules::class,'module_id','id');
    }
}
