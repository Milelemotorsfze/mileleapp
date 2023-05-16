<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOfIndent extends Model
{
    use HasFactory;
    public const LOI_CATEGORY_REAL = "Real";
    public const LOI_CATEGORY_SPECIAL = "Special";
    public const LOI_SUBMISION_STATUS = "New";
    public const LOI_STATUS = "New";
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function LOIDocuments()
    {
        return $this->hasMany(LetterOfIndentDocument::class);
    }
    public function letterOfIndentItems()
    {
        return $this->hasMany(LetterOfIndentItem::class,'letter_of_indent_id');
    }
}
