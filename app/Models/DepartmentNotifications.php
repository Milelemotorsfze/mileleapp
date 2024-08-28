<?php

namespace App\Models;
use App\Models\Masters\MasterDepartment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentNotifications extends Model
{
    use HasFactory;
    protected $table = 'department_notifications';

    public function departments()
    {
        return $this->belongsToMany(MasterDepartment::class, 'dnaccess', 'department_notifications_id', 'master_departments_id');
    }

    public function viewedLogs()
    {
        return $this->hasMany(DnViewLog::class, 'department_notifications_id');
    }
}
