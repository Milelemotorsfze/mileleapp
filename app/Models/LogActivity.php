<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $table = "log_activities";
    protected $fillable = [
        'user_id',
        'status',
        'ip'
    ];
    public function logineUser()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
