<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorCode extends Model
{
    use HasFactory;
    public const INTERIOR = "int";
    public const EXTERIOR = "ex";

}
