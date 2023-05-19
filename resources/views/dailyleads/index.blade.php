@extends('layouts.table')
@section('content')
  <div class="card-header">
  <style>
  .wrapper {
    display: table-cell;
    width: 100%;
    vertical-align: middle;
  }

  .arrow-steps .step {
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
  .arrow-steps .step:before {
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

  .arrow-steps .step:before {
    right: auto;
    left: 0;
    border-left: 17px solid #fff;
    z-index: 0;
  }

  .arrow-steps .step:first-child:before {
    border: none;
  }

  .arrow-steps .step span {
    position: relative;
  }

  .arrow-steps .step span:before {
    opacity: 0;
    content: "✔";
    position: absolute;
    top: -2px;
    left: -20px;
    transition: opacity 0.3s ease 0.5s;
  }

  .arrow-steps .step.done span:before {
    opacity: 1;
  }

  .arrow-steps .step.done {
    background-color: #06ac77;
  }

  .arrow-steps .step.done:after {
    border-left: 17px solid #06ac77;
  }

  .arrow-steps .step.current {
    color: #fff;
    background-color: #5156be;
  }

  .arrow-steps .step.current:after {
    border-left: 17px solid #5156be;
  }

  .arrow-steps .step.clicked {
    background-color: #3498DB;
  }

  .arrow-steps .step.clicked.current:after {
    border-left: 17px solid #3498DB;
  }

  @media (max-width: 765px) {
    .arrow-steps .step {
      min-width: auto;
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
    }
  }
</style>
    <h4 class="card-title">
      Leads Info
    </h4>
    @can('daily-leads-create')
    <a class="btn btn-sm btn-success float-end" href="{{ route('users.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Demand
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('calls.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Lead
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Inquiry</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Prospecting</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Qualification</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Demands</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Quotation</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Review Quotation</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Closed</a>
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
      <li><a class="dropdown-item" href="{{ route('dailyleads.prospecting',$calls->id) }}">Prospecting</a></li>
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
  <script>
  jQuery(document).ready(function() {
    var steps = jQuery(".step");
    var dataTable = null; // Variable to store the DataTables instance

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
          // Reinitialize DataTables with the updated table content
          dataTable = jQuery("#dtBasicExample2").DataTable();
        },
        error: function() {
          // Handle error
        }
      });
    });
  });
</script>
@endsection