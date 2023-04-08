@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Bill of Lading Form
    </h4>
    @can('blfrom-create')
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('blfrom.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New BL Form
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
    @can('blfrom-list')
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($data as $key => $blfrom)
                  <tr data-id="1">
                    <td>{{ ++$i }}</td>
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
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($convertedleads as $key => $callsl)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>        
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