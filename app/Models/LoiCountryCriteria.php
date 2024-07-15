<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoiCountryCriteria extends Model
{
    use HasFactory;
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    const YES = 1;
    const NO = 2;
    const NONE = '';
    
    protected $appends = [
        'restricted_model_lines',
        'allowed_model_lines'
    ];
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function getRestrictedModelLinesAttribute() {
        $country_id = $this->country_id;
        $restrictedModelLines =  MasterModelLines::with('restricredOrAllowedModelLines')
                        ->whereHas('restricredOrAllowedModelLines', function($query) use($country_id) {
                            $query->where('is_restricted', true)
                            ->where('country_id', $country_id);
                        })->pluck('model_line')->toArray();

          
         return $restrictedModelLines;              
    }
    public function getAllowedModelLinesAttribute() {
        $country_id = $this->country_id;
        $allowedModelLines =  MasterModelLines::with('restricredOrAllowedModelLines')
                        ->whereHas('restricredOrAllowedModelLines', function($query) use($country_id) {
                            $query->where('is_allowed', true)
                            ->where('country_id', $country_id);
                        })->pluck('model_line')->toArray();

          
         return $allowedModelLines;
        }
}
