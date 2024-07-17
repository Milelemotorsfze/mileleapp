<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankMaster extends Model
{
    use HasFactory;
    protected $table = 'bank_master';
    protected $fillable = [
        'bank_name',
        'branch_name',
        'contact_person',
        'address',
        'contact_number',
    ];
    public function bankAccounts()
    {
        return $this->hasMany(BankAccounts::class, 'bank_master_id');
    }
}
