<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportReleaseHistory extends Model
{
    use HasFactory;
    protected $table = "passport_release_histories";
    protected $fillable = [
        'passport_release_id',
        'icon',
        'message',
    ];
}
