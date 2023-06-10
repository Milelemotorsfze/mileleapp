@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Warranty Info</h4>
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('warranty.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Warranty</a>  
  </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>No</th>
              <th>Policy Name</th>
              <th>Eligibility Years</th>
              <th>Elegibility Mileage</th>
              <th>Extended Warranty Period</th>
              <th>Extended Warranty Mileage</th>
              <th>Claim Limit</th>
              <th>Vehicle Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <div hidden>{{$i=0;}}</div>
            
          </tbody>
        </table>
      </div>
    </div>
 

@endsection

