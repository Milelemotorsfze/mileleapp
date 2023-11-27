@if(isset($data->jobDescription))
    <div class="col-xxl-6 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-header" style="background-color:#e8f3fd;">
                <h4 class="card-title"><center>Job Description</center></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Job Title :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->jobTitle->name ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Department :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->department->name ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Location :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->location->name ?? ''}}, {{$data->jobDescription->location->address ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Reporting To :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->reportingTo->name ?? ''}} ( 'JOB POSITION',{{$data->jobDescription->reportingTo->email ?? ''}} )</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Job Purpos :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->job_purpose ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Duties and Responsibilities (Generic) of the position :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->duties_and_responsibilities ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Skills required at fulfill the position :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->skills_required ?? ''}}</span>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-6">
                        <label for="choices-single-default" class="form-label">Position Qualifications (Academic & Professional) :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-6">
                        <span>{{$data->jobDescription->position_qualification ?? ''}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif