<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalespersonOfClients extends Model
{
    use HasFactory;
    protected $table = 'salesperson_clients';
    public function client()
    {
        return $this->belongsTo(Clients::class, 'clients_id');
    }
}
