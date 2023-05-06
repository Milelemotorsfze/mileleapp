@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New Request for Hiring</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('hiring.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
			<form action="{{ route('hiring.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 btncenter">
                        <div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Job Title</label>
                            <input type="text" name="job_title" placeholder="Enter Job TItle" class="form-control">
                        </div>
    					<div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Job Details</label>
                            <textarea name="job_details" placeholder="Enter Job Details" class="form-control"></textarea>
                        </div>
    					<div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Role</label>
                            <input type="text" name="job_role" placeholder="Enter Role of the Candidate" class="form-control">
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Education</label>
                            <input type="text" name="job_education" placeholder="Enter Higher Education Require" class="form-control">
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Experaince</label>
                            <input type="text" name="job_experiance" placeholder="Enter Experaicne in Years" class="form-control">
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Skills</label>
                            <textarea name="job_skills" placeholder="Enter Require Set of Skills" class="form-control"></textarea>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="basicpill-firstname-input" class="form-label">Other Notes</label>
                            <textarea name="job_other" placeholder="Enter Side Note Here" class="form-control"></textarea>
                        </div>
    			     </div>
                    <div class="clearfix"></div>
                    </br>
    		        <div class="col-lg-6 col-md-6 btncenter">
                        <input type="submit" name="submit" value="SUBMIT" class="btn btn-success btn-sm btncenter" />
    		        </div>
                </div>
            </form>
		</br>
    </div>
@endsection




