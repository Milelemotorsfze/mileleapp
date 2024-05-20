<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuitlpleAgents extends Model
{
    use HasFactory;
    protected $table = 'muitlple_agents';
    protected $fillable = ['agents_id', 'quotations_id'];
    public function agent() {
        return $this->belongsTo(Agents::class, 'agents_id', 'id');
    }
}
