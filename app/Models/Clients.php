<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'clients';

    public const CUSTOMER_TYPE_INDIVIDUAL = "Individual";
    public const CUSTOMER_TYPE_COMPANY = "Company";
    public const CUSTOMER_TYPE_GOVERMENT = "Government";

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
    public function clientCountries()
    {
        return $this->hasMany(ClientCountry::class,'client_id','id');
    }
    public function clientDocuments()
    {
        return $this->hasMany(ClientDocument::class,'client_id','id');
    }
    public function getIsDeletableAttribute() {

        $isExistLOI = LetterOfIndent::where('client_id', $this->id)->count();

        if ($isExistLOI <= 0) {
            $isExistSalespersonOfClients = SalespersonOfClients::where('clients_id', $this->id)->count();
            if($isExistSalespersonOfClients <= 0) {

                $isExistClientAccount = ClientAccount::where('clients_id', $this->id)->count();
                if($isExistClientAccount <= 0) {

                    $isExistClientLeads = ClientLeads::where('clients_id', $this->id)->count();
                    if($isExistClientLeads <= 0) {

                        $isExistClientCountries = ClientCountry::where('client_id', $this->id)->count();
                        if($isExistClientCountries <= 0) {
                            $isExistPFI = PFI::where('client_id', $this->id)->count();
                            if($isExistPFI <= 0) {
                                $isExistClientDocuments = ClientDocument::where('client_id', $this->id)->count();
                                if($isExistClientDocuments <= 0) {
                                    return true;
                                }
                            }
                        }
                        
                    }
                }             
            }
        }
           
        return false;
    }
}
