<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varaint extends Model
{
    use HasFactory;
    protected $table = 'varaints';
    public function availableColors()
    {
        return $this->hasMany(AvailableColour::class, 'varaint_id');
    }
}
