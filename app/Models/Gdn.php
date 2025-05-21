<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gdn extends Model
{
    use HasFactory;

    protected $table = 'gdn';

    protected $fillable = [
        'date',
        'gdn_number',
    ];

    public $timestamps = true;
}
