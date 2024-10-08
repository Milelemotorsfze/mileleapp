<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function removed_vehicles() {
        return $this->hasMany(WOVehicles::class, 'deleted_comment_id')->withTrashed();
    }
    public function new_vehicles()
    {
        return $this->hasMany(CommentVehicleMapping::class, 'comment_id')
            ->where('type', 'store');
    }
    public function updated_vehicles() {
        return $this->hasMany(CommentVehicleMapping::class, 'comment_id')
            ->where('type', 'update');
    }
    public function mentionedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_user', 'comment_id', 'user_id');
    }
}
