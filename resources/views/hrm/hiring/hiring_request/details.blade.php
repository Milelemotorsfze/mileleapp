<div class="row">
  <div class="col-xxl-6 col-lg-6 col-md-6">
    <div class="col-xxl-12 col-lg-12 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Request Date :</label>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6">
                <span>{{ $data->request_date ?? '' }}</span>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <label for="choices-single-default" class="form-label"> Current Status :</label>
            </div>
            <div class="col-lg-4 col-md-3 col-sm-6">
                @if($data->current_status == 'Rejected')            
                    <label class="badge badge-soft-danger">{{ $data->current_status ?? '' }}</label>
                @elseif($data->current_status == 'Approved')
                    <label class="badge badge-soft-success">{{ $data->current_status ?? '' }}</label>
                @else
                    <label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label>
                @endif
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
              <label for="choices-single-default" class="form-label"> Reporting To With Position:</label>
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
          <div class="col-lg-3 col-md-4 col-sm-6">
              <label for="choices-single-default" class="form-label">Replacement For Employee :</label>
          </div>
          <div class="col-lg-9 col-md-8 col-sm-6">
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