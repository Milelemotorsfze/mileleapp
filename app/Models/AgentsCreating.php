<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentsCreating extends Model
{
    use HasFactory;
    protected $table = "agents_creating";

    protected $fillable = [
        'agents_id',
        'created_by',
    ];
}
