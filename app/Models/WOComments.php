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
}
