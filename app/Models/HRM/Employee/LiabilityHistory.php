<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiabilityHistory extends Model
{
    use HasFactory;
    protected $table = "liability_histories";
    protected $fillable = [
        'liability_id',
        'icon',
        'message',
    ];
}
