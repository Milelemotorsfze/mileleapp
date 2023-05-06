@extends('layouts.table')
@section('content')
<div class="card-header">
  <h4 class="card-title">Hiring Details</h4>
  <a class="btn btn-sm btn-success float-end" href="{{ route('hiring.create') }}" text-align: right>
    <i class="fa fa-plus" aria-hidden="true"></i> Create Hiring Request
  </a>
  <div class="clearfix"></div>
  <br>
  <ul class="nav nav-pills nav-fill">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New Requests</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#tab2">Approved By Management</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#tab3">Short Listed CVs</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#tab4">Called for Interview</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#tab5">Interview with Management</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="pill" href="#tab6">Date of Joining</a>
    </li>
  </ul>      
</div>
<div class="tab-content">
  <div class="tab-pane fade show active" id="tab1"> 
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Sr No.</th>
              <th>Job Title</th>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Qualification</th>
              <th>Experiance</th>
              <th>Skills</th>
              <th>Other</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody><div hidden>{{$i=0;}}</div>
            @foreach ($hiring as $hiringDetails)
            <tr>
              <td>{{ ++$i }}</td>
              <td>{{ $hiringDetails['job_title']}}</td>
              <td>{{ $hiringDetails['job_details']}}</td>
              <td>{{ $hiringDetails['job_role']}}</td>
              <td>{{ $hiringDetails['job_education']}}</td>
              <td>{{ $hiringDetails['job_experiance']}}</td>
              <td>{{ $hiringDetails['job_skills']}}</td>
              <td>{{ $hiringDetails['job_other']}}</td>
              <!-- View Not Defined Yet. Change HR-View to HR-Edit -->
              @can('HR-view')
              <td>
                <a href="{{ route('hiring.edit', $hiringDetails->id) }}" class="btn btn-sm btn-success">
                  <i data-feather="edit-3"></i>
                </a>
                &nbsp;
              </td>
              @endcan
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>  
  <div class="tab-pane fade show" id="tab2">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Qualification</th>
              <th>Skills</th>
              <th>Other</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div> 
    </div>  
  </div> 
  <div class="tab-pane fade show" id="tab3">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Status</th>
              <th>Resumes</th>
            </tr>
          </thead>
          <tbody> 
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="tab-pane fade show" id="tab4">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample4" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Resumes</th>
              <th>Interview Date & Time</th>
            </tr>
          </thead>
          <tbody> 
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="tab-pane fade show" id="tab5">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample5" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Resumes</th>
              <th>Departmental Remarks</th>
              <th>Interview Date & Time</th>
            </tr>
          </thead>
          <tbody> 
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="tab-pane fade show" id="tab6">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample6" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>Job Details</th>
              <th>Roles</th>
              <th>Resumes</th>
              <th>Departmental Remarks</th>
              <th>Management Remarks</th>
              <th>Date of Joining</th>
            </tr>
          </thead>
          <tbody> 
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection