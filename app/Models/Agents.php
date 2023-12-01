<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents extends Model
{
    use HasFactory;
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
        return $this->hasManyThrough(SalesOrder::class, AgentCommission::class, 'agents_id', 'id', 'id', 'so_id');
    }
}
