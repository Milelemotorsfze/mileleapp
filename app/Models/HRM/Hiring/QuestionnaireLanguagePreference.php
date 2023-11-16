<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireLanguagePreference extends Model
{
    use HasFactory;
    protected $table = "questionnaire_language_preferences";
    protected $fillable = [
        'questionnaire_id', 
        'language_id'
    ];
}
