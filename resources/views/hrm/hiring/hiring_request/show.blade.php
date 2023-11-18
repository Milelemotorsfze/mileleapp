@extends('layouts.table')
<style>
  .texttransform {
    text-transform: capitalize;
  }
</style>
@section('content')
<div class="card-header">
	<h4 class="card-title"> Employee Hiring Request Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	@if (Session::has('error'))
	<div class="alert alert-danger" >
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('error') }}
	</div>
	@endif
	@if (Session::has('success'))
	<div class="alert alert-success" id="success-alert">
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('success') }}
	</div>
	@endif
</div>
<div class="card-body">
<div class="row">
  <div class="col-xxl-6 col-lg-6 col-md-6">
    <div class="col-xxl-12 col-lg-12 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Request Date :</label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6">
                <span>{{ $data->request_date ?? '' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xxl-12 col-lg-12 col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Department Information</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Department Name :</label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6">
                <span>{{ $data->department_name ?? '' }}</span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Department Location :</label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6">
                <span>{{ $data->department_location ?? '' }}</span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Requested By :</label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6">
                <span>{{ $data->requested_by_name ?? '' }}</span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Requested Job Title :</label>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6">
                <span>{{ $data->requested_job_name ?? '' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xxl-6 col-lg-6 col-md-6">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Position Information</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Reporting To With Position :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->reporting_to_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Experience Level :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->experience_level_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Salary Range(AED) :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Work Time :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Number Of Openings :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->number_of_openings ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Type Of Role :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->type_of_role_name ?? '' }}</span>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
              <label for="choices-single-default" class="form-label"> Replacement For Employee :</label>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-6">
              <span>{{ $data->replacement_for_employee_name ?? '' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
	</div>
  <div class="row">
    <div class="col-xxl-12 col-lg-12 col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Detailed Explanation Of New Hiring</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <span>{{$data->explanation_of_new_hiring ?? ''}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    @if(isset($data->questionnaire))
      <div class="col-xxl-6 col-lg-6 col-md-6">
        <div class="col-xxl-12 col-lg-12 col-md-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title">Questionnaire</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Designation Type :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                  <span>{{$data->questionnaire->designation_type_name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Designation Name :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->designation->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Years of experience in specific job role :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->no_of_years_of_experience_in_specific_job_role ?? ''}} Years</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Reporting structure :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->reporting_structure_name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Work location :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->workLocation->name ?? ''}} , {{$data->questionnaire->workLocation->address ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Number of hiring :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->number_of_openings ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Hiring time :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->hiring_time_name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Working hours :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->work_time_start ?? ''}} - {{$data->questionnaire->work_time_end ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Education :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->education_name ?? ''}}</span>
                </div>
                @if($data->questionnaire->education == 'pg_in_same_specialisation_or_related_to_department')
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Education Certificates:</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span>{{$data->questionnaire->education_certificates ?? ''}}</span>
                  </div>
                @endif
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Certification :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->certification ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Any specific industry experience :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->specificIndustryExperience->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Any specific company experience :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->specific_company_experience ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Salary range :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->salary_range_start_in_aed ?? ''}} AED - {{$data->questionnaire->salary_range_end_in_aed ?? ''}} AED</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Visa Type :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->visaType->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Nationality :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->nationalities->nationality ?? ''}} ( {{$data->questionnaire->nationalities->name ?? ''}} )</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Age Range :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->min_age ?? ''}} - {{$data->questionnaire->max_age ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Any additional langauage other than English :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                  <span>
                    @if(isset($data->questionnaire->additionalLanguages))
                      @if(count($data->questionnaire->additionalLanguages) > 0)
                        @foreach($data->questionnaire->additionalLanguages as $additionalLanguage)
                          {{$additionalLanguage->languageName->name}} ,
                        @endforeach
                      @endif
                    @endif
                  </span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Did he required to travel for work purpose ? :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->required_to_travel_for_work_purpose ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Do candidate requires multiple industry experience ? :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->requires_multiple_industry_experience ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Team Handling experience is required ? :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->team_handling_experience_required ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Driving license :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->driving_licence ?? ''}}</span>
                </div>
                @if($data->questionnaire->driving_licence == 'yes')
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Own car :</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span class="texttransform">{{$data->questionnaire->own_car ?? ''}}</span>
                  </div>
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Fuels Expenses covered by :</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span class="texttransform">{{$data->questionnaire->fuel_expenses_by ?? ''}}</span>
                  </div>
                @endif
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Is shortlisted candidate required to work on trial ? :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->required_to_work_on_trial ?? ''}}</span>
                </div>
                @if($data->questionnaire->required_to_work_on_trial == 'yes')
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Number Of Trial Days :</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span>{{$data->questionnaire->number_of_trial_days ?? ''}} Days</span>
                  </div>
                @endif             
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Is comission involved along with the salary ? :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->commission_involved_in_salary ?? ''}}</span>
                </div>
                @if($data->questionnaire->commission_involved_in_salary == 'yes' && $data->questionnaire->commission_type == 'amount')
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Commission Amount :</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span>{{$data->questionnaire->commission_amount ?? ''}} AED</span>
                  </div>
                @elseif($data->questionnaire->commission_involved_in_salary == 'yes' && $data->questionnaire->commission_type == 'percentage')              
                  <div class="col-lg-6 col-md-3 col-sm-6">
                      <label for="choices-single-default" class="form-label">Commission Percentage :</label>
                  </div>
                  <div class="col-lg-6 col-md-9 col-sm-6">
                      <span>{{$data->questionnaire->commission_percentage ?? ''}} %</span>
                  </div>
                @endif
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Top 3 skills or mandatory work experience :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->mandatory_skills ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Interviewed by :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->interviewedBy->name ?? ''}} ( {{$data->questionnaire->interviewedBy->email ?? ''}} )</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Objective of the job Purpose of the job opening :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->job_opening_purpose_objective ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Screening questions :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->screening_questions ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Technical test :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->technical_test ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Job description during trial working :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->trial_work_job_description ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Stake holders for job evaluation :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->job_evaluation_stake_holders ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Recuritment Source :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->recruitmentSource->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Experience :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->experience_name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Travel Experience:</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->travel_experience ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Divison or Department:</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->department->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Career Level :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->carrerLevel->name ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Current or Past Employer Size :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->current_or_past_employer_size_start ?? ''}} - {{$data->questionnaire->current_or_past_employer_size_end ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Trial Pay :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->trial_pay_in_aed ?? ''}} AED</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Out of Office visits :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->out_of_office_visit ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Remote work :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->remote_work ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">International Business Trips required :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span class="texttransform">{{$data->questionnaire->international_business_trip_required ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Probation length :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->probation_length_in_months ?? ''}} Months</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Probation Pay :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->probation_pay_amount_in_aed ?? ''}} AED</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Incentives, Perks, & Bonus :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->incentives_perks_bonus ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">KPI :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->kpi ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Practical test :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->practical_test ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Trial objectives and evaluation method :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->trial_objectives_and_evaluation_method ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Duties & Tasks :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->duties_and_tasks ?? ''}}</span>
                </div>
                <div class="col-lg-6 col-md-3 col-sm-6">
                    <label for="choices-single-default" class="form-label">Next Career Path :</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-6">
                    <span>{{$data->questionnaire->nextCareerPath->name ?? ''}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="col-xxl-6 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">History</h4>
        </div>
        <div class="card-body">
          <div class="col-xxl-12 col-lg-12 col-md-12">
            @if(isset($data->history))
            @foreach($data->history as $history)
            <div class="row">
              <div class="col-xxl-1 col-lg-1 col-md-1">
                <img src="{{ asset('icons/' . $history->icon) }}" style="width:30px;height:30px;">
              </div>
              <div class="col-xxl-11 col-lg-11 col-md-11">
              {{$history->message ?? ''}} </br> <span style="color:gray">{{$history->created_at ?? ''}}</span>
              </div>
            </div>
            </br>
            @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
   
  </div>
</div>
@endsection
@push('scripts')
<script>
	$(document).ready(function () {
	
	    $('.delete-button').on('click',function(){
	        let id = $(this).attr('data-id');
	        let url =  $(this).attr('data-url');
	        var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
	            if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        _method: 'DELETE',
	                        id: 'id',
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success:function (data) {
	                        location.reload();
	                        alertify.success('Item Deleted successfully.');
	                    }
	                });
	            }
	        }).set({title:"Delete Item"})
	    });
	})
	function inputNumberAbs(currentPriceInput)
	{
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d.]/g, '');
	    if(val.split('.').length>2)
	    {
	        val =val.replace(/\.+$/,"");
	    }
	    input.value = val;
	}
</script>
@endpush