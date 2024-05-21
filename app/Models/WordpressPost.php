<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WordpressPost extends Model
{
    protected $connection = 'wordpress'; // Use the 'wordpress' connection
    protected $table = 'wp_posts'; // Specify the table name
}
