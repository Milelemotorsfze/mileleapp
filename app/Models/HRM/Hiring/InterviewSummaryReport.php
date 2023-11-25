<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSummaryReport extends Model
{
    use HasFactory;
    protected $table = "interview_summary_reports";
    protected $fillable = [
        'hiring_request_id',
        'candidate_name',
        'nationality',
        'gender',
        'name_of_interviewer',
        'date_of_interview',
        'date_of_telephonic_interview',
        'telephonic_interview',
        'rate_dress_appearance',
        'rate_body_language_appearance',
        'date_of_first_round',
        'first_round',
        'date_of_second_round',
        'second_round',
        'date_of_third_round',
        'third_round',
        'date_of_forth_round',
        'forth_round',
        'date_of_fifth_round',
        'fifth_round',
        'final_evaluation_of_candidate',
        'candidate_selected',
        'status',
        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',
        'action_by_division_head',
        'division_head_id',
        'division_head_action_at',
        'comments_by_division_head',
        'seleced_status',
        'selected_status_by',
        'selected_status_at',
        'selected_status_comment',
        'selected_hiring_request_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
