<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandList extends Model
{
    use HasFactory;
    public function fiveMonthDemands()
    {
        $years = [];
        $currentMonths = [];
        $currentMonth = date('n') - 2;
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M y', mktime(0,0,0,$i, 1, date('Y')));
            $years[] = date('y', mktime(0,0,0,$i, 1, date('Y')));
            $currentMonths[] = date('M', mktime(0,0,0,$i, 1, date('Y')));
        }

        return $this->hasMany(MonthlyDemand::class)->whereIn('month', $currentMonths)
            ->whereIn('year', $years);
    }
    public function monthlyDemands() {

        return $this->hasMany(MonthlyDemand::class);
    }
}
