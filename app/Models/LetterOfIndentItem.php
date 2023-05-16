<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOfIndentItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class);
    }
}
