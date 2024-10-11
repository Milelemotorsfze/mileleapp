<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterOfIndentDocument extends Model
{
    use HasFactory, SoftDeletes;
    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class,'letter_of_indent_id');
    }

}
