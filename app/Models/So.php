<?php

namespace App\Models;

use App\Models\HRM\Employee\EmployeeProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class So extends Model
{
    use HasFactory;
    protected $table = 'so';
    protected $fillable = [
        'sales_person_id',
        'so_date',
        'so_number',
        'quotation_id',
        'logistics_detail_id',
        'notes',
        'created_at',
        'updated_at',
        'sales_type',
        'total',
        'receiving',
        'paidinso',
        'paidinperforma',
        'created_by',
        'updated_by',
    ];
    public $timestamps = false;

    public function vehicles()
    {
        return $this->hasMany(Vehicles::class);
    }
    public function salesperson()
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    public function call()
    {
        return $this->hasOneThrough(Calls::class, Quotation::class, 'id', 'id', 'quotation_id', 'calls_id');
    }
    public function so_variants()
    {
        return $this->hasMany(SoVariant::class, 'so_id');
    }
    public function so_logs()
    {
        return $this->hasMany(Solog::class, 'so_id');
    }

    public function quotationDetail()
    {
        return $this->hasOne(\App\Models\QuotationDetail::class, 'quotation_id', 'quotation_id');
    }

    public function quotationVersionFiles()
    {
        return $this->hasMany(\App\Models\QuotationFile::class, 'quotation_id', 'quotation_id');
    }

    public function empProfile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id', 'created_by');
    }
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function leadClosed()
    {
        return $this->hasOne(Closed::class, 'so_id');
    }
    public function soItems()
    {
        return $this->hasMany(Soitems::class, 'so_id');
    }
}
