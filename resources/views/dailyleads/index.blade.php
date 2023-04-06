@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Daily Leads Info
    </h4>
    @can('daily-leads-create')
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('users.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Lead 
      </a>
    @endcan
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Calls</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Managment Remarks On Leads</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Approved Leads By Management</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Already In Stock / Rejected</a>
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
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($pendingdata as $key => $leads)
                  <tr data-id="1">
                  <td>{{ $leads->date }}</td>
                    <td>{{ $leads->name }}</td>     
                    <td>{{ $leads->phone }}</td> 
                    <td>{{ $leads->email }}</td>  
                    <td>{{ $leads->sales_person }}</td>  
                    <td>{{ $leads->remarks }}</td>    
                    <td>
                    <div class="btn-group">
  <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-edit" aria-hidden="true"></i>
  </button>
    <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="{{ route('users.create') }}">Convert to Leads</a></li>
    <li><a class="dropdown-item" href="{{ route('users.create') }}">Already In Stock</a></li>
    <li><a class="dropdown-item" href="{{ route('users.create') }}">Rejected</a></li>
  </ul>
</div>
                    </td>        
                  </tr>
                @endforeach
              </tbody>
            </table>
            </br>
</br>
</br>
</br>
</br>
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
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($pendingdata as $key => $leads)
                <td>{{ ++$i }}</td>
                  <tr data-id="1">
                    <td>{{ $leads->date }}</td>
                    <td>{{ $leads->name }}</td>     
                    <td>{{ $leads->phone }}</td> 
                    <td>{{ $leads->email }}</td>  
                    <td>{{ $leads->sales_person }}</td>  
                    <td>{{ $leads->remarks }}</td>       
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('daily-leads-list')
        <div class="tab-pane fade show" id="tab3">
          <div class="card-body">
            <div class="table-responsive">
              <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($pendingdata as $key => $leads)
                  <tr data-id="1">
                    <td>{{ $leads->date }}</td>
                    <td>{{ $leads->name }}</td>               
                  </tr>
                @endforeach
                 </tbody>
                 </br>
                </table>
              </div>
            </div>
          </div>
        @endcan
      </div><!-- end tab-content-->
    </div>
  </div>
@endsection