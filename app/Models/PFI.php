<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PFI extends Model
{
    use HasFactory;
    protected $table = "pfi";
    public const PFI_STATUS_NEW = 'New';
    public function letterOfIndent()
    {
        return $this->belongsTo(LetterOfIndent::class);
    }

}
