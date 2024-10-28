<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDocument extends Model
{
    use HasFactory;
    
    protected $fillable = ['lead_id', 'document_name', 'document_path', 'document_type'];

    // Relation to the Lead (if you have a Lead model)
    public function lead()
    {
        return $this->belongsTo(Calls::class);
    }
}
