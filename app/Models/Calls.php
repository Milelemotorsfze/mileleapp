<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;
    protected $table = 'calls';
    protected $fillable = [
        'name',
        'email',
        'sales_person',
        'remarks',
        'phone',
        // 'secondary_phone_number',
        'source',
        'status',
        'language',
        'location',
        'created_by',
        'type',
        'region',
        'strategies_id',
        'priority',
        'custom_brand_model',
        'csr_price',
        'csr_currency',
        'created_at',
        'leadtype',
        'customer_coming_type',
    ];
    public $timestamps = false;
    // public function callRequirement()
    // {
    //     return $this->hasMany(CallsRequirement::class,'lead_id','id');
    // }
    // public function modelsBrands()
    // {
    //     return $this->hasOne(CallsRequirement::class, 'lead_id', 'id')
    //         ->selectRaw('GROUP_CONCAT(CONCAT(brands.brand_name, " - ", master_model_lines.model_line) SEPARATOR ", ")')
    //         ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
    //         ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id');
    // }
    public function salesperson()
    {
        return $this->belongsTo(User::class, 'sales_person', 'id');
    }
    public function leadssouces()
    {
        return $this->belongsTo(LeadSource::class, 'source', 'id');
    }
    public function strategies()
    {
        return $this->belongsTo(Strategy::class, 'strategies_id', 'id');
    }
    public function requirements()
    {
        return $this->hasOne(CallsRequirement::class, 'lead_id', 'id');
    }
    public function prospectingleads()
    {
        return $this->hasOne(Prospecting::class, 'calls_id', 'id');
    }
    public function negotiationleads()
    {
        return $this->hasOne(Negotiation::class, 'calls_id', 'id');
    }
    public function quotationleads()
    {
        return $this->hasOne(Quotation::class, 'calls_id', 'id');
    }
    public function rejectionleads()
    {
        return $this->hasOne(Rejection::class, 'call_id', 'id');
    }
    public function salesdemandleads()
    {
        return $this->hasOne(Salesdemand::class, 'calls_id', 'id');
    }
    public function closed()
    {
        return $this->hasOne(Closed::class, 'call_id', 'id');
    }
    public function quotation()
    {
        return $this->hasOne(Quotation::class, 'calls_id');
    }
    }
