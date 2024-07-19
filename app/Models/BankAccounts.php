<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccounts extends Model
{
    use HasFactory;
    protected $table = 'bank_accounts';
    public function transactions()
    {
        return $this->hasMany(BankAccountLog::class);
    }
    public function bank()
    {
        return $this->belongsTo(BankMaster::class);
    }
}
