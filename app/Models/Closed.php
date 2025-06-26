<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Closed extends Model
{
    use HasFactory;
    protected $table = 'lead_closed';
    protected $fillable = [
        'calls_id',
        'date',
        'so_number',
        'created_by',
        'sales_notes',
    ];
    public $timestamps = false;

    public function call()
    {
        return $this->belongsTo(Calls::class, 'call_id');
    }

}
