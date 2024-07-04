<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountLog extends Model
{
    use HasFactory;
    protected $table = 'bank_account_log';
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
