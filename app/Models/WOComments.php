<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOComments extends Model
{
    use HasFactory;
    protected $table = "w_o_comments";
    protected $fillable = ['work_order_id','text', 'parent_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(WOComments::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(WOComments::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(CommentFile::class, 'comment_id');
    }
    public function wo_histories() {
        return $this->hasMany(WORecordHistory::class, 'comment_id');
    }
    public function new_vehicles() {
        return $this->hasMany(WOVehicles::class, 'comment_id')->withTrashed();
    }
    public function removed_vehicles() {
        return $this->hasMany(WOVehicles::class, 'deleted_comment_id')->withTrashed();
    }
    public function updated_vehicles()
    {
        return $this->hasManyThrough(
            WOVehicles::class,                // The related model
            WOVehicleRecordHistory::class,    // The intermediary model
            'comment_id',                     // Foreign key on the intermediary model (WOVehicleRecordHistory)
            'id',                             // Foreign key on the final model (WOVehicles)
            'id',                             // Local key on the WOComments model
            'w_o_vehicle_id'                  // Local key on the intermediary model (WOVehicleRecordHistory)
        )
        ->join('w_o_vehicle_record_histories as wrh', 'wrh.w_o_vehicle_id', '=', 'w_o_vehicles.id')
        ->join('w_o_comments as wc', 'wrh.comment_id', '=', 'wc.id')
        ->whereNotNull('w_o_vehicles.updated_by')
        ->whereColumn('wrh.comment_id', 'wc.id')
        ->select('w_o_vehicles.*')
        ->distinct()  // Ensure the results are unique
        ->withTrashed();  // Ensure correct columns are selected
    }
}
