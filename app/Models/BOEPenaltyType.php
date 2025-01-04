<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BOEPenaltyType extends Model
{
    use HasFactory;
    protected $table = 'boe_penalty_type';

    protected $fillable = [
        'boe_penalties_id',
        'penalty_types_id',
    ];

    /**
     * Relationship with BOEPenalty.
     */
    public function boePenalty()
    {
        return $this->belongsTo(BOEPenalty::class, 'boe_penalties_id');
    }

    /**
     * Relationship with PenaltyType.
     */
    public function penaltyType()
    {
        return $this->belongsTo(PenaltyType::class, 'penalty_types_id');
    }
}
