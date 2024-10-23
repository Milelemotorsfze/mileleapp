<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoDocsStatus extends Model
{
    use HasFactory;
    protected $table = "wo_docs_status";
    protected $fillable = [
        'wo_id',
        'is_docs_ready',
        'documentation_comment',
        'doc_status_changed_by',
        'doc_status_changed_at',
        'declaration_number',
        'declaration_date',
    ];
    protected $casts = [
        'doc_status_changed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','doc_status_changed_by');
    }
}
