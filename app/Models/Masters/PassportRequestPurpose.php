<?php

namespace App\Models\Masters;

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
