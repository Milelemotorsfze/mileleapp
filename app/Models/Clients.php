<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = 'clients';

    public const CUSTOMER_TYPE_INDIVIDUAL = "Individual";
    public const CUSTOMER_TYPE_COMPANY = "Company";
    public const CUSTOMER_TYPE_GOVERMENT = "Government";
    public const CUSTOMER_TYPE_NGO = "NGO";

    protected $appends = [
        'is_deletable'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function getIsDeletableAttribute() {

        $isExistinInventory = LetterOfIndent::where('client_id', $this->id)->count();

        if ($isExistinInventory <= 0) {
            return true;
        }
        return false;
    }
}
