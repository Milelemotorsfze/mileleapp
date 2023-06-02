<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpDoc extends Model
{
    use HasFactory;
    protected $table = 'emp_doc';
    protected $fillable = [
        'emp_profile_id',
        'document_name',
        'document_path',
    ];
    public $timestamps = false;
}
