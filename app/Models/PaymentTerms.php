<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    use HasFactory;
    protected $table = 'payment_terms';
    public function milestones()
    {
        return $this->hasMany(Milestone::class,'payment_terms_id','id');
    }
}
