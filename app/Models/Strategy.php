<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Access\Authorizable;

class Strategy extends Model
{
    use HasFactory;
    protected $table = 'strategies';
    protected $fillable = [
        'name',
        'status',
        'lead_source_id',
        'created_by',
    ];
    public $timestamps = false;
    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }
}
