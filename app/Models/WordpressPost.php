<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordpressPost extends Model
{
    use HasFactory;
    protected $connection = 'wordpress';
    protected $table = 'mm_posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
