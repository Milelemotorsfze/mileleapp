@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Daily Calls Info
    </h4>
    @can('daily-leads-create')
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('calls.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Daily Calls 
      </a>
    @endcan
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Calls to Leads</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Calls to Leads Converted</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
    @can('daily-leads-list')
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Sales Person</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($data as $key => $calls)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ $calls->date }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>  
                    <td>{{ $calls->sales_person }}</td>  
                    <td>{{ $calls->remarks }}</td>             
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
    @endcan
    @can('daily-leads-list')
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Sales Person</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($convertedleads as $key => $callsl)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                  <td>{{ $callsl->date }}</td>
                    <td>{{ $callsl->name }}</td>     
                    <td>{{ $callsl->phone }}</td> 
                    <td>{{ $callsl->email }}</td>  
                    <td>{{ $callsl->sales_person }}</td>  
                    <td>{{ $callsl->remarks }}</td>            
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      </div>
    </div>
  </div>
@endsection