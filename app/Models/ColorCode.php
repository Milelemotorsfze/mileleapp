<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ColorCode extends Model
{
    use HasFactory;

    public const INTERIOR = "int";
    public const EXTERIOR = "ex";

    protected $table = 'color_codes';

    protected $fillable = [
        'name',
        'belong_to',
        'parent',
        'created_by'
    ];


    public function dpColorCodes()
    {
        return $this->hasMany(DpColorCode::class, 'color_code_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
