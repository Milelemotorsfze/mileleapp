@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
                    @endphp
                    @if ($hasPermission)
  <div class="card-header">
  <style>
  .wrapper 
  {
    display: table-cell;
    width: 100%;
    vertical-align: middle;
  }
  .arrow-steps .step 
  {
    font-size: 14px;
    text-align: center;
    color: #222;
    cursor: pointer;
    margin: 0 3px;
    padding: 10px 10px 10px 30px;
    min-width: 400px;
    float: left;
    position: relative;
    background-color: #dbdbdb;
    border-radius: 13px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    transition: background-color 0.2s ease;
  }
  .arrow-steps .step:after,
  .arrow-steps .step:before 
  {
    content: " ";
    position: absolute;
    top: 3px;
    right: -11px;
    width: 0;
    height: 0;
    border-top: 19px solid transparent;
    border-bottom: 17px solid transparent;
    border-left: 17px solid #dbdbdb;
    z-index: 2;
    transition: border-color 0.2s ease;
  }
  .arrow-steps .step:before 
  {
    right: auto;
    left: 0;
    border-left: 17px solid #fff;
    z-index: 0;
  }
  .arrow-steps .step:first-child:before 
  {
    border: none;
  }
  .arrow-steps .step span 
  {
    position: relative;
  }
  .arrow-steps .step span:before 
  {
    opacity: 0;
    content: "✔";
    position: absolute;
    top: -2px;
    left: -20px;
    transition: opacity 0.3s ease 0.5s;
  }
  .arrow-steps .step.done span:before 
  {
    opacity: 1;
  }
  .arrow-steps .step.done 
  {
    background-color: #06ac77;
  }
  .arrow-steps .step.done:after 
  {
    border-left: 17px solid #06ac77;
  }
  .arrow-steps .step.current 
  {
    color: #fff;
    background-color: #5156be;
  }
  .arrow-steps .step.current:after 
  {
    border-left: 17px solid #5156be;
  }
  .arrow-steps .step.clicked 
  {
    background-color: #3498DB;
  }
  .arrow-steps .step.clicked.current:after 
  {
    border-left: 17px solid #3498DB;
  }
  @media (max-width: 765px) 
  {
    .arrow-steps .step {
      min-width: auto;
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
    }
  }
  input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-outer-spin-button 
{ 
    -webkit-appearance: none; 
    -moz-appearance: none;
    appearance: none; 
    margin: 0; 
}
</style>
    <h4 class="card-title">
      Leads Info
    </h4>
    <!-- @can('user-view')
    <a class="btn btn-sm btn-success float-end" href="{{ route('users.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Demand
      </a>
      @endcan -->
      @can('sales-view')
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('dailyleads.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Lead
      </a>

      <div class="clearfix"></div>
<br>
    @endcan
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Inquiry</a>
      </li>
      @can('user-view')
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Prospecting</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Qualification</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Demands</a>
      </li>
      @endcan
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Quotation</a>
      </li>
      @can('user-view')
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Review Quotation</a>
      </li>
      @endcan
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab7">Closed</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab8">Rejected Inquiry</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
    @can('sales-view')
      <div class="tab-pane fade show active" id="tab1">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($pendingdata as $key => $calls)
                <tr data-id="1">
                    <td>{{ date('d-M-Y', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->language }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>   
    <td>{{ $calls->status }}</td>       
                    <td>
                    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
    @can('sales-view')
      <li><a class="dropdown-item" href="{{ route('dailyleads.prospecting',$calls->id) }}">Prospecting</a></li>
      <li><a class="dropdown-item" href="#">Qualificaton</a></li>
      <li><a class="dropdown-item" href="#">Demand</a></li>
      <li><a class="dropdown-item" href="#">Closed</a></li>
      @endcan
      <li><a class="dropdown-item" href="#" onclick="openModal('{{ $calls->id }}')">Quotation</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $calls->id }}')">Rejected</a></li>
    </ul>
  </div>
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
      <div class="modal fade" id="quotationModal" tabindex="-1" aria-labelledby="quotationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="quotationModalLabel">Quotation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
              <input type="date" class="form-control" id="date" value="">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="deal-value-input" class="form-label">Deal Value:</label>
            </div>
            <div class="col-md-8">
              <div class="input-group">
                <span class="input-group-text">AED</span>
                <input type="number" class="form-control" id="deal-value-input" aria-label="Deal Value">
                <span class="input-group-text">.00</span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="sales-notes"></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Upload Document:</label>
            </div>
            <div class="col-md-8">
              <input type="file" class="form-control" id="document-upload">
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveQuotations()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectionModalLabel">Rejection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="date-input" class="form-label">Date:</label>
          </div>
          <div class="col-md-8">
            <input type="date" class="form-control" id="date-input" value="">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="reason" class="form-label">Reason:</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="reason" value="">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="sales-notess" class="form-label">Sales Notes:</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control" id="salesnotes"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveRejection()">Save Changes</button>
      </div>
    </div>
  </div>
</div>
    @endcan
    @can('sales-view')
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
        <div class="wrapper">	
        <div class="arrow-steps clearfix">
          <div class="step current"> <span>Initial Contact</span> </div>
          <div class="step"> <span>Fellow Up</span> </div>
          <div class="step"> <span>Need Analysis</span> </div>
          <div class="step"> <span>Interested</span> </div>
			</div>
</div>
    </br>
    </br>
          <div class="table-responsive">
          <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
  <thead>
    <tr>
      <th>Date</th>
      <th>Purchase Type</th>
      <th>Customer Name</th>
      <th>Customer Phone</th>
      <th>Customer Email</th>
      <th>Brands & Models</th>
      <th>Custom Model & Brand</th>
      <th>Preferred Language</th>
      <th>Customer Update</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody id="table-body">
  @foreach ($intialcallsdata as $key => $leads)
  <tr data-id="1">
                    <td>{{ date('d-m-Y (H:i A)', strtotime($leads->created_at)) }}</td>
                    <td>{{ $leads->type }}</td>
                    <td>{{ $leads->name }}</td>     
                    <td>{{ $leads->phone }}</td> 
                    <td>{{ $leads->email }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $leads->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $leads->custom_brand_model }}</td>
                    <td>{{ $leads->language }}</td>
                    <td>{{ $leads->language }}</td>
                    <td>
                    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="#">Prospecting</a></li>
      <li><a class="dropdown-item" href="#">Qualificaton</a></li>
      <li><a class="dropdown-item" href="#">Demand</a></li>
      <li><a class="dropdown-item" href="#">Quotation</a></li>
      <li><a class="dropdown-item" href="#">Rejected</a></li>
    </ul>
  </div>
                    </td>
                    </td>       
                  </tr>
  @endforeach
</tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('sales-view')
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
		    @can('sales-view')
      <div class="tab-pane fade show" id="tab5">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($qutationsdata as $key => $calls)
                <tr data-id="1">
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->language }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>   
    <td>{{ $calls->status }}</td>       
                    <td>
                    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
    @can('user-view')
      <li><a class="dropdown-item" href="{{ route('dailyleads.prospecting',$calls->id) }}">Prospecting</a></li>
      <li><a class="dropdown-item" href="#">Qualificaton</a></li>
      <li><a class="dropdown-item" href="#">Demand</a></li>
      @endcan
      <li><a class="dropdown-item" href="#" onclick="openModalclosed('{{ $calls->id }}')">Closed</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $calls->id }}')">Rejected</a></li>
    </ul>
  </div>
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
      
  <div class="modal fade" id="dealcloseModal" tabindex="-1" aria-labelledby="dealcloseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dealcloseModalLabel">Closed</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="dealdate" class="form-label">Date:</label>
          </div>
          <div class="col-md-8">
            <input type="date" class="form-control" id="dealdate" value="">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="so-number" class="form-label">SO Number:</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="so-number" value="">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="dealsalesNotes" class="form-label">Sales Notes:</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control" id="dealsalesNotes"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="savecloserss()">Save Changes</button>
      </div>
    </div>
  </div>
</div>
    @endcan
    @can('sales-view')
      <div class="tab-pane fade show" id="tab7">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample7" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($closeddata as $key => $calls)
                <tr data-id="1">
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->language }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>   
    <td>{{ $calls->status }}</td>              
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
    @can('sales-view')
      <div class="tab-pane fade show" id="tab8">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample8" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rejectiondata as $key => $calls)
                <tr data-id="1">
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->language }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>   
    <td>{{ $calls->status }}</td>           
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
      </div><!-- end tab-content-->
    </div>
  </div>
  <script>
  jQuery(document).ready(function() {
    var steps = jQuery(".step");
    var dataTable = null; 

    steps.on("click", function() {
      var clickedStep = jQuery(this);
      var currentIndex = steps.index(clickedStep);
      steps.removeClass("current done");
      clickedStep.addClass("current");
      steps.slice(0, currentIndex).addClass("done");

      // Get the step status based on the clickedStep
      var stepStatus = clickedStep.find("span").text();

      // Make an AJAX request to the Laravel controller
      jQuery.ajax({
        url: "{{ route('processStep') }}",
        method: "POST",
        data: {
          status: stepStatus,
          _token: '{{ csrf_token() }}' // Include the CSRF token
        },
        success: function(response) {
          console.log("no");
          // Destroy the previous DataTables instance if it exists
          if (dataTable) {
            dataTable.destroy();
          }
          // Clear the previous data
          jQuery("#table-body").empty();
          response.forEach(function(item) {
    var row = '<tr data-id="1">' +
        '<td>' + item.created_at + '</td>' +
        '<td>' + item.type + '</td>' +
        '<td>' + item.name + '</td>' +
        '<td>' + item.phone + '</td>' +
        '<td>' + item.email + '</td>';

    var modelLines = '';

    item.model_lines.forEach(function(modelLine, index) {
        if (index > 0) {
            modelLines += ', ';
        }
        modelLines += modelLine;
    });

    row += '<td>' + modelLines + '</td>' +
        '<td>' + item.custom_brand_model + '</td>' +
        '<td>' + item.language + '</td>' +
        '<td>' + item.remarks + '</td>' +
        '<td>' + item.remarks + '</td>' +
        '</tr>';

    jQuery("#table-body").append(row);
});
          dataTable = jQuery("#dtBasicExample2").DataTable();
        },
        error: function() {
        }
      });
    });
  });
  function openModal(callId) {
  $('#quotationModal').data('call-id', callId);
  $('#quotationModal').modal('show');
}
// function openModalr(callId) {
//   $('#rejectionModal').data('call-id', callId);
//   $('#rejectionModal').modal('show');
// }
function saveQuotations() {
  var callId = $('#quotationModal').data('call-id');
  var date = document.getElementById('date').value;
  var dealValue = document.getElementById('deal-value-input').value;
  var salesNotes = document.getElementById('sales-notes').value;
  var fileInput = document.getElementById('document-upload');
  
  if (date === '') {
    alert('Please select a date');
    return;
  }
  
  if (dealValue === '') {
    alert('Please enter the deal value');
    return;
  }
  
  if (fileInput.files.length === 0) {
    alert('Please upload a document');
    return;
  }
  
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('dealValue', dealValue);
  formData.append('salesNotes', salesNotes);
  formData.append('file', fileInput.files[0]);
  
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');  
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.qoutations') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          console.log('Quotation saved successfully');
          alert('Quotation saved successfully');
          location.reload();
        } else {
          console.error('Error saving quotation');
          alert('Error saving quotation');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function openModalr(callId) {
  $('#rejectionModal').data('callId', callId);
  document.getElementById('date-input').value = '';
  document.getElementById('reason').value = '';
  document.getElementById('sales-notes').value = '';
  $('#rejectionModal').modal('show');
}
function saveRejection() {
  var callId = $('#rejectionModal').data('callId');
  var date = document.getElementById('date-input').value;
  var reason = document.getElementById('reason').value;
  var salesNotes = document.getElementById('salesnotes').value;
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('reason', reason);
  formData.append('salesNotes', salesNotes);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.rejection') }}', true); // Replace '/profile/rejection' with the actual URL path to your server-side endpoint
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          console.log('Rejection saved successfully');
          alert('Rejection saved successfully');
          location.reload();
        } else {
          console.error('Error saving rejection');
          alert('Error saving rejection');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function openModalclosed(callId) {
    $('#dealcloseModal').data('callId', callId);
    document.getElementById('dealdate').value = '';
    document.getElementById('so-number').value = '';
    document.getElementById('dealsalesNotes').value = '';
    $('#dealcloseModal').modal('show');
  }
  function savecloserss() {
    var callId = $('#dealcloseModal').data('callId');
    var dealdate = document.getElementById('dealdate').value;
    var sonumber = document.getElementById('so-number').value;
    var dealsalesNotes = document.getElementById('dealsalesNotes').value;
    var formData = new FormData();
    formData.append('callId', callId);
    formData.append('dealdate', dealdate);
    formData.append('sonumber', sonumber);
    formData.append('dealsalesNotes', dealsalesNotes);
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route('sales.closed') }}', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            console.log('Closed saved successfully');
            alert('Closed saved successfully');
            location.reload();
          } else {
            console.error('Error saving Closed');
            alert('Error saving Closed');
          }
        } else {
          console.error('Request failed with status ' + xhr.status);
          alert('Request failed. Please try again.');
        }
      }
    };
    xhr.send(formData);
  }
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection