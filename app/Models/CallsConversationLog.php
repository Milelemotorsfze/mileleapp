<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallsConversationLog extends Model
{
    use HasFactory;

    protected $table = 'calls_conversation_log';

    protected $fillable = ['lead_id', 'conversation'];
}
