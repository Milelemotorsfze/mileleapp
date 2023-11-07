<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivities extends Model
{
    use HasFactory;
    protected $table = 'user_activities';
    protected $fillable = [
        'activity',
        'users_id',
        'created_at'
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'users_id');
}
}
