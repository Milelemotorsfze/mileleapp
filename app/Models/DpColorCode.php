<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\ColorCode;

class DpColorCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dp_color_codes';

    protected $fillable = [
        'color_code_id',
        'color_code_values',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function colorCode()
    {
        return $this->belongsTo(ColorCode::class, 'color_code_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
