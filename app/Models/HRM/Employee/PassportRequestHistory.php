<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportRequestHistory extends Model
{
    use HasFactory;
    protected $table = "passport_request_histories";
    protected $fillable = [
        'passport_request_id',
        'icon',
        'message',
    ];
}
