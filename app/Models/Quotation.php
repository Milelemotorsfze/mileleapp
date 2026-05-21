<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = [
        'calls_id',
        'date',
        'deal_value',
        'sales_notes',
        'file_path',
        'nature_of_deal',
    ];
    public $timestamps = false;
    public function quotationdetails()
    {
        return $this->hasOne(QuotationDetail::class);
    }
    public function so()
    {
        return $this->hasOne(So::class, 'quotation_id');
    }

    public function call()
    {
        return $this->belongsTo(Calls::class, 'calls_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    /**
     * Exclude soft-deleted quotations when the column exists (same rule as Daily Leads listing).
     */
    public function scopeActive(Builder $query): Builder
    {
        if (Schema::hasColumn($this->getTable(), 'deleted_at')) {
            return $query->whereNull($this->getTable().'.deleted_at');
        }

        return $query;
    }

    public function scopeForCall(Builder $query, int $callId): Builder
    {
        return $query->where('calls_id', $callId);
    }

    /**
     * Latest quotation for a call (MAX id), used by Quoted tab and proforma edit — keeps PDF/fields aligned.
     */
    public static function latestForCall(int $callId): ?self
    {
        return static::query()
            ->forCall($callId)
            ->active()
            ->orderByDesc('id')
            ->first();
    }
}
