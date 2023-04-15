@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Daily Leads Info
    </h4>
    @can('daily-leads-create')
    <a class="btn btn-sm btn-success float-end" href="{{ route('users.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Demand / Lead
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Inquiry</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Inquiry To Quotations</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Quotations To Sales</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Pending Demands</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Approved Demands</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Rejected Demands</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab7">Rejected Inquiry</a>
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
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Demand</th>
                  <th>Source</th>
                  <th>Language</th>
                  <th>Location</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($pendingdata as $key => $calls)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                     @php
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $calls->demand }}</td> 
                    <td>{{ $calls->source }}</td>
                    <td>{{ $calls->language }}</td>
                    <td>{{ $calls->location }}</td>
                    @php
                    $text = $calls->remarks;
                    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
                    @endphp
                    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>  
                    <td><a data-placement="top" class="btn btn-sm btn-success" href="{{ route('quotation.show',$calls->id) }}"><i class="fas fa-file-invoice" aria-hidden="true" title="Add Into Qoutation"></i></a>
                    <button type="button" href="" class="btn btn-sm btn-info" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <button type="button" href="" class="btn btn-sm btn-danger" data-bs-toggle="dropdown" aria-expanded="false" title="Rejected">
                    <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                    </td>
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