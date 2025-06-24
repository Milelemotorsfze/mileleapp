<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'email',
        'phone',
        'identification_file',
        'id_number',
        'id_category'
    ];

    public function multipleAgents() {
        return $this->hasMany(MuitlpleAgents::class, 'agents_id', 'id');
    }
    public function commissions()
    {
        return $this->hasMany(AgentCommission::class, 'agents_id', 'id');
    }
    public function quotations()
    {
        return $this->hasManyThrough(Quotation::class, AgentCommission::class, 'agents_id', 'id', 'id', 'quotation_id');
    }

    public function salesOrders()
    {
        return $this->hasManyThrough(So::class, AgentCommission::class, 'agents_id', 'id', 'id', 'so_id');
    }
}
