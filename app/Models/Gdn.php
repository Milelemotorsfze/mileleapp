<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gdn extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'gdn';
    protected $fillable = [
        'date',
        'gdn_number',
    ];
    public $timestamps = true;
    protected $dates = ['deleted_at'];

}
