<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'purpose_of_letter_request',
        'asked_by_company_name',
        'employee_id',
        'name',
        'status',
        'comments',
    ];
}
