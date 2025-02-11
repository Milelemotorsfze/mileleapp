<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterModelDescription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "master_model_descriptions";
    protected $fillable = [
        'steering',
        'model_line_id',
        'master_vehicles_grades_id',
        'engine',
        'fuel_type',
        'transmission',
        'drive_train',
        'window_type',
        'model_description',
        'created_by',
    ];

    protected $appends = [
        'is_deletable'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function modelLine()
    {
        return $this->belongsTo(MasterModelLines::class,'model_line_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
    public function getIsDeletableAttribute() {

        $isExistinQuotationItem = QuotationItem::select('model_description_id')->where('model_description_id', $this->id)->count();
        if ($isExistinQuotationItem <= 0) {
            $isExistinVariant = Varaint::select('master_model_descriptions_id','model_detail')
                                        ->where('master_model_descriptions_id', $this->id)
                                        ->orwhere('model_detail', $this->model_description)->count();
            if($isExistinVariant <= 0) {
                $isExistinWO = WOVehicles::select('model_description')->where('model_description', $this->model_description)->count();
                if($isExistinWO <= 0) {
                    $isExistAddonTypes = AddonTypes::select('model_number')->where('model_number', $this->id)->count();
                    if($isExistAddonTypes <= 0) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
