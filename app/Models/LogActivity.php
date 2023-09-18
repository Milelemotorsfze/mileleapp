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
        'ip',
        'mac_address',
        'browser_name',
        'device_name'
    ];
    public function logineUser()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
