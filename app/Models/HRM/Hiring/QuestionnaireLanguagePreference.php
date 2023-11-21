<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;
use Illuminate\Database\Eloquent\SoftDeletes;
class QuestionnaireLanguagePreference extends Model
{
    use HasFactory;
    protected $table = "questionnaire_language_preferences";
    public $timestamps = false;
    protected $fillable = [
        'questionnaire_id', 
        'language_id'
    ];
    public function languageName() {
        return $this->hasOne(Language::class,'id','language_id');
    }
}
