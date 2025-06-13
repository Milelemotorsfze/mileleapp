<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\So;

class SoFinalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'removed_so_ids',
        'finalized_so_id',
        'linked_so_number',
        'remarks',
        'is_finalized',
        'created_by',
    ];

    protected $casts = [
        'removed_so_ids' => 'array',
        'is_finalized' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizedSo()
    {
        return $this->belongsTo(So::class, 'finalized_so_id');
    }
}