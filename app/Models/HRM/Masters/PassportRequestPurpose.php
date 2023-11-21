<?php

namespace App\Models\HRM\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportRequestPurpose extends Model
{
    use HasFactory;
    protected $table = "passport_request_purposes";
    protected $fillable = [
        'type',
        'name',
    ];
}
