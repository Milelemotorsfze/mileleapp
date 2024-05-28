<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordpressPostMeta extends Model
{
    use HasFactory;
    protected $connection = 'wordpress';
    protected $table = 'mm_postmeta';
    public $timestamps = false;
}
