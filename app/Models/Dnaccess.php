<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dnaccess extends Model
{
    use HasFactory;
    protected $table = 'dnaccess';
    public function notification()
    {
        return $this->belongsTo(DepartmentNotifications::class, 'department_notifications_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
