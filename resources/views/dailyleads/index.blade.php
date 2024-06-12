@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
                    @section('content')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                    @endphp
                    @if ($hasPermission)
  <div class="card-header">
  <style>
    #dtBasicExample2 {
        width: 100%;
    }
    @keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
/* Add styles for the badges */
.badge {
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
}
.badge-success {
    background-color: #28a745; /* Green color for 'Signed' */
    color: #fff;
}
.badge-danger {
    background-color: #dc3545; /* Red color for 'Unsigned' */
    color: #fff;
}
.blink {
    animation: blink 1s infinite;
}
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
      @if (Session::has('success'))
          <div class="alert alert-success" id="success-alert">
              <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
              {{ Session::get('success') }}
          </div>
      @endif
      <a class="btn btn-sm btn-info float-end" href="{{ route('salescustomers.index') }}" text-align: right>
        <i class="fa fa-users" aria-hidden="true"></i> Customers
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-primary float-end" href="{{ route('booking.index') }}" text-align: right>
        <i class="fa fa-info" aria-hidden="true"></i> Bookings
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('dailyleads.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Lead
      </a>
     
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <!-- <a class="btn btn-sm btn-primary float-end" href="" text-align: right>
        <i class="fa fa-info" aria-hidden="true"></i> Bookings (Coming Soon)
      </a> -->
      <div class="clearfix"></div>
<br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Inquiry</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab9">FollowUp</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Prospecting</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Demands</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Quotation</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Negotiation</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab8">Pre-Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Sales Order</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab7">Rejected</a>
      </li>
    </ul>
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Priority</th>
                  <th>Lead Date</th>
                  <th>Remining Times</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($pendingdata as $key => $calls)
                    <tr data-id="{{$calls->id}}">
                <td>
                    @if ($calls->priority == "High")
                        <i class="fas fa-circle blink" style="color: red;"> Hot</i>
                    @elseif ($calls->priority == "Normal")
                        <i class="fas fa-circle" style="color: green;"> Normal</i>
                    @elseif ($calls->priority == "Low")
                        <i class="fas fa-circle" style="color: orange;"> Low</i>
                    @else
                        <i class="fas fa-circle" style="color: black;"> Regular</i>
                    @endif
                </td>
                    <td>{{ date('d-M-Y', strtotime($calls->created_at)) }}</td>
                    <td id="remaining-time-{{ $key }}" data-assign-time="{{ $calls->assign_time }}"></td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>
                    <td>
                    <div class="dropdown">
                    <a href="#" role="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $calls->phone }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style=" min-width: 0; padding: 0;">
                        <li>
                            <a class="dropdown-item" href="#" onclick="openWhatsApp('{{ $calls->phone }}')">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="tel:{{ $calls->phone }}">
                                <i class="fas fa-phone"></i>
                            </a>
                        </li>
                        </ul>
                    </div>
                    </td>
                    <td><a href="mailto:{{ $calls->email }}">{{ $calls->email }}</a></td>
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
                    <td>{{ $calls->location }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>
                    <td>
                    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="#" onclick="openModalfellowup('{{ $calls->id }}')">FollowUp</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModalp('{{ $calls->id }}')">Prospecting</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModald('{{ $calls->id }}')">Unique Inquiry / Demand</a></li>
      <!-- <li><a class="dropdown-item" href="#" onclick="openModal('{{ $calls->id }}')">Quotation</a></li> -->
      <li><a class="dropdown-item"href="{{route('qoutation.proforma_invoice',['callId'=> $calls->id]) }}">Quotation</a></li>
      <!-- <li><a class="dropdown-item" href="#" onclick="openModalqualified('{{ $calls->id }}')">Negotiation</a></li> -->
      <!-- <li><a class="dropdown-item" href="{{ route('booking.create', ['call_id' => $calls->id]) }}">Booking Vehicles</a></li> -->
      <!-- <li><a class="dropdown-item" href="">Booking (Coming Soon)</a></li> -->
      <!-- <li><a class="dropdown-item" href="#" onclick="openModalclosed('{{ $calls->id }}')">Sales Order</a></li> -->
      <!-- <li><a class="dropdown-item"href="{{route('salesorder.createsalesorder',['callId'=> $calls->id]) }}">Sales Order</a></li> -->
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
      <div class="modal fade" id="openfellowupdatemodel" tabindex="-1" aria-labelledby="openfellowupdatemodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="openfellowupdatemodelLabel">Follow Up (Update)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Time:</label>
            </div>
            <div class="col-md-8">
            <input type="time" class="form-control" id="time" value="{{ date('H:i') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Method:</label>
            </div>
            <div class="col-md-8">
            <select class="form-select" id="method">
            <option value="WhatsApp">WhatsApp</option>
            <option value="Email">Email</option>
            <option value="Call">Call</option>
            <option value="Direct">Direct</option>
            <option value="SMS">SMS</option>
          </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
            <textarea class="form-control" id="sales-notesfoup"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savefollowupdate()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="openfellowupmodel" tabindex="-1" aria-labelledby="openfellowupmodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="openfellowupmodelLabel">Follow Up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="dateup" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Time:</label>
            </div>
            <div class="col-md-8">
            <input type="time" class="form-control" id="time" value="{{ date('H:i') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Method:</label>
            </div>
            <div class="col-md-8">
            <select class="form-select" id="method">
            <option value="Email">Email</option>
            <option value="Call">Call</option>
            <option value="Direct">Direct</option>
            <option value="SMS">SMS</option>
            <option value="WhatsApp">WhatsApp</option>
          </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
            <textarea class="form-control" id="sales-notesfo"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savefollowup()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
      <div class="modal fade" id="prospectingmodel" tabindex="-1" aria-labelledby="prospectingmodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="prospectingmodelLabel">Prospecting</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
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
              <label for="document-upload" class="form-label">Upload Picture:</label>
            </div>
            <div class="col-md-8">
              <input type="file" class="form-control" id="screenshort">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Set Priority:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" id="priority" name="priority">
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                    <option value="High">High</option>
                </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveprospecting()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="demandmodel" tabindex="-1" aria-labelledby="demandmodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="demandmodelLabel">Unique Inquiry / Demand</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="sales-notesdemands"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savedemand()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="ClosedModal" tabindex="-1" aria-labelledby="ClosedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ClosedModalLabel">Sales Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
            <input type="date" class="form-control" id="date-closed" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
          <div class="col-md-4">
            <label for="reason" class="form-label">SO Number</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="so_number-closed" value="">
          </div>
        </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="deal-value-input" class="form-label">Deal Value:</label>
            </div>
            <div class="col-md-8">
              <div class="input-group">
              <select name = "currency" class="form-select" id="currency-select-closed">
              <option value="AED">AED</option>
              <option value="USD">USD</option>
              <option value="EURO">EURO</option>
            </select>
                <input type="number" class="form-control" id="deal-value-input-closed" aria-label="Deal Value">
                <span class="input-group-text">.00</span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="sales-notes-closed"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveclosed()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
      <div class="modal fade" id="qualified" tabindex="-1" aria-labelledby="qualifiedLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="qualifiedLabel">Negotiation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Date:</label>
            </div>
            <div class="col-md-8">
              <input type="date" class="form-control" id="date-negotiation" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="deal-value-input" class="form-label">Deal Value:</label>
            </div>
            <div class="col-md-8">
              <div class="input-group">
              <select name = "currency" class="form-select" id="currency-select-negotiation">
                  <option value="AED">AED</option>
                  <option value="USD">USD</option>
                  <option value="EURO">EURO</option>
                </select>
                <input type="number" class="form-control" id="deal-value-input-negotiation" aria-label="Deal Value">
                <span class="input-group-text">.00</span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="sales-notes-negotiation"></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Upload Re-Qoutation:</label>
            </div>
            <div class="col-md-8">
              <input type="file" class="form-control" id="document-upload-negotiation">
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="savenegotiation()">Save Changes</button>
        </div>
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
              <input type="date" class="form-control" id="dateqoutation" value="{{ date('Y-m-d') }}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="deal-value-input" class="form-label">Deal Value:</label>
            </div>
            <div class="col-md-8">
              <div class="input-group">
              <select name = "currency" class="form-select" id="currency-select">
              <option value="AED">AED</option>
              <option value="USD">USD</option>
              <option value="EURO">EURO</option>
            </select>
    <input type="number" class="form-control" id="deal-value-input-qoutation" aria-label="Deal Value">
    <span class="input-group-text">.00</span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Sales Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="sales-note-qoutation"></textarea>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="document-upload" class="form-label">Upload Document:</label>
            </div>
            <div class="col-md-8">
              <input type="file" class="form-control" id="document-upload-qoutation">
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
  <div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="linkModalLabel">Quotation Link</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <p id="linkInModal"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="copyLink()">Copy</button>
      </div>
	        </div>
    </div>
  </div>
  <div class="modal fade" id="uploadingquotation" tabindex="-1" aria-labelledby="uploadingquotationLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadingquotationLabel">Uploading Signed Quotation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="callId" id="modalCallId">
          <input type="file" name="quotationFile" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="uploadingQuotations()">Upload File</button>
      </div>
      </form>
    </div>
  </div>
</div>
  <div class="modal fade" id="vinsModal" tabindex="-1" aria-labelledby="vinsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="vinsModalLabel">VINs</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id ="vinsModalContent">
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            <input type="date" class="form-control" id="date-input-reject" value="{{ date('Y-m-d') }}">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="reason" class="form-label">Reason:</label>
          </div>
          <div class="col-md-8">
  <select class="form-control" id="reason-reject">
    <option value="">Select Reason</option>
    <option value="Brand not available">Brand not available</option>
    <option value="Model not available">Model not available</option>
    <option value="Variant not available">Variant not available</option>
    <option value="Price Issue">Price Issue</option>
    <option value="Not Interested">Not Interested</option>
    <option value="Others">Others</option>
  </select>
  <input type="text" class="form-control" id="other-reason" style="display: none;" placeholder="Specify Other Reason">
</div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="sales-notess" class="form-label">Sales Notes:</label>
          </div>
          <div class="col-md-8">
            <textarea class="form-control" id="salesnotes-reject"></textarea>
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
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel">File Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="fileViewer" width="100%" height="500" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fileModaln" tabindex="-1" aria-labelledby="fileModalLabeln" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabeln">File Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="fileViewern" width="100%" height="500" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
      <div class="tab-pane fade show" id="tab2">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Inquiry Date</th>
                  <th>Inquiry Notes</th>
                  <th>Purchaser Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
        <div class="tab-pane fade show" id="tab3">
        <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Demand Date</th>
                  <th>Demand Notes</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
          </div>
      <div class="tab-pane fade show" id="tab4">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Demand Date</th>
                  <th>Demand Notes</th>
                  <th>Qoutation Date</th>
                  <th>Deal Values</th>
                  <th>Qoutation Notes</th>
                  <th>View Qoutation</th>
                  <th>Signature Status</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab5">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Demand Date</th>
                  <th>Demand Notes</th>
                  <th>Qoutation Date</th>
                  <th>Deal Values</th>
                  <th>Qoutation Notes</th>
                  <th>View Qoutation</th>
                  <th>Negotiation Date</th>
                  <th>Deal Values</th>
                  <th>Negotiation Notes</th>
                  <th>View Re-Qoutation</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab6">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample6" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Demand Date</th>
                  <th>Demand Notes</th>
                  <th>Qoutation Date</th>
                  <th>Qoutation Values</th>
                  <th>Qoutation Notes</th>
                  <th>View Qoutation</th>
                  <!-- <th>Negotiation Date</th>
                  <th>Negotiation Values</th>
                  <th>Negotiation Notes</th>
                  <th>View Re-Qoutation</th> -->
                  <th>Sales Date</th>
                  <th>Sales Values</th>
                  <th>Sales Notes</th>
                  <th>So Number</th>
                  <th>SO Update</th>
                  <!-- <th>Booking Vehicles</th> -->
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab8">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample8" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Qoutation ID</th>
                  <th>Qoutation Date</th>
                  <th>Qoutation Values</th>
                  <th>Qoutation Notes</th>
                  <th>Model Line</th>
                  <th>Exterior Colour</th>
                  <th>Interior Colour</th>
                  <th>Model Year</th>
                  <th>Qty</th>
                  <th>Country</th>
                  <th>Description</th>
                  <th>Status</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab7">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample7" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Demand Date</th>
                  <th>Demand Notes</th>
                  <th>Qoutation Date</th>
                  <th>Qoutation Values</th>
                  <th>Qoutation Notes</th>
                  <th>View Qoutation</th>
                  <!-- <th>Negotiation Date</th>
                  <th>Negotiation Values</th>
                  <th>Negotiation Notes</th>
                  <th>View Re-Qoutation</th> -->
                  <th>Reject Date</th>
                  <th>Reject Reason</th>
                  <th>Reject Notes</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab9">
      <br>
      <!-- <div class="row">
  <div class="col-lg-1">
    <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
  </div>
</div> -->
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample9" class="table table-striped table-editable table-edits table" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Lead Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Followup Date</th>
                  <th>Followup Time</th>
                  <th>Method</th>
                  <th>Sales Notes</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      </div><!-- end tab-content-->
    </div>
  </div>
  <script>
    function uploadingQuotations() {
  var formData = new FormData();
  var fileInput = document.querySelector('input[name="quotationFile"]');
  var callId = document.getElementById('modalCallId').value;
  formData.append('quotationFile', fileInput.files[0]);
  formData.append('callId', callId);
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url: '/upload-quotation-file',
    type: 'POST',
    data: formData,
    processData: false,  // tell jQuery not to process the data
    contentType: false,  // tell jQuery not to set contentType
    success: function (data) {
      alert('File uploaded successfully.');
      $('#uploadingquotation').modal('hide');
    },
    error: function (xhr, status, error) {
      alert('Error: ' + error.message);
    }
  });
}
      $(document).ready(function() {
            var url = '{{ asset('storage/quotation_files/') }}';
            var QuotationUrl = url + '/' + '{{request()->quotationFilePath}}';
            if('{{ request()->quotationFilePath }}' ) {
              window.open(QuotationUrl, '_blank');
          }
      });
     function openModalfile(filePath) {
    const baseUrl = "{{ asset('storage/') }}"; // The base URL to the public storage directory
    const fileUrl = baseUrl + '/' + filePath; // Add a slash between baseUrl and filePath
    console.log('File URL:', fileUrl); // Log the URL to the console
    $('#fileViewer').attr('src', fileUrl);
    $('#fileModal').modal('show');
}
$('#fileModal').on('hidden.bs.modal', function () {
    $('#fileViewer').attr('src', '');
});
function openModalfilen(filePath) {
  const baseUrl = "{{ asset('storage/') }}"; // The base URL to the public storage directory
    const fileUrl = baseUrl + '/' + filePath; // Add a slash between baseUrl and filePath
    console.log('File URL:', fileUrl); // Log the URL to the console
    $('#fileViewern').attr('src', fileUrl);
    $('#fileModaln').modal('show');
}
$('#fileModaln').on('hidden.bs.modal', function () {
    $('#fileViewern').attr('src', '');
});
  jQuery(document).ready(function() {
    var steps = jQuery(".step");
    var dataTable = null;
    steps.on("click", function() {
      var clickedStep = jQuery(this);
      var currentIndex = steps.index(clickedStep);
      steps.removeClass("current done");
      clickedStep.addClass("current");
      steps.slice(0, currentIndex).addClass("done");
      var stepStatus = clickedStep.find("span").text();
      jQuery.ajax({
        url: "{{ route('processStep') }}",
        method: "POST",
        data: {
          status: stepStatus,
          _token: '{{ csrf_token() }}' // Include the CSRF token
        },
        success: function(response) {
          console.log("no");
          if (dataTable) {
            dataTable.destroy();
          }
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
function openModalclosed(callId) {
  $('#ClosedModal').data('call-id', callId);
  $('#ClosedModal').modal('show');
}
function openModalp(callId) {
  $('#prospectingmodel').data('call-id', callId);
  $('#prospectingmodel').modal('show');
}
function openModalfellowup(callId) {
  $('#openfellowupmodel').data('call-id', callId);
  $('#openfellowupmodel').modal('show');
}
function openModalfellowupdate(callId) {
  // Set the callId as a data attribute of the modal
  $('#openfellowupdatemodel').data('call-id', callId);
  $.ajax({
        url: '/update-followup-info-data/' + callId,
        type: 'GET',
        success: function(data) {
            $('#date').val(data.date);
            $('#time').val(data.time);
            $('#method').val(data.method);
            $('#sales-notesfoup').val(data.sales_notes);
            $('#openfellowupdatemodel').modal('show');
        },
        error: function(xhr, status, error) {
            console.error(error);
            // Handle error appropriately, e.g., show an error message
        }
    });
}

function openModald(callId) {
  $('#demandmodel').data('call-id', callId);
  $('#demandmodel').modal('show');
}
function openModalqualified(callId) {
  $('#qualified').data('call-id', callId);
  $('#qualified').modal('show');
}
function openModalr(callId) {
  $('#rejectionModal').data('call-id', callId);
  $('#rejectionModal').modal('show');
}
function displayModal(response) {
  var modalContent = $('#vinsModalContent');
  modalContent.empty();
  response.forEach(function (data) {
    if (data.quotationVins && data.quotationVins.length > 0) {
      // Start the table with Bootstrap classes and additional styling
      modalContent.append('<div class="table-responsive" style="width:100%;"><table class="table table-bordered table-striped table-editable table-edits table">');
      modalContent.append('<thead class="bg-soft-secondary"><tr><th class="text-center" style="border: 1px solid #dee2e6; width:50%;">Description</th><th class="text-center" style="border: 1px solid #dee2e6; width:50%;">VIN Numbers</th></tr></thead>');
      modalContent.append('<tbody>');
      // Get the rowspan for the description
      var rowspan = data.quotationVins.length;
      data.quotationVins.forEach(function (vinEntry, index) {
        // Display the description only in the first row
        if (index === 0) {
          modalContent.append('<tr><td rowspan="' + rowspan + '" style="border: 1px solid #dee2e6;">' + data.description + '</td><td class="text-center" style="border: 1px solid #dee2e6;">' + vinEntry.vin + '</td></tr>');
        } else {
          modalContent.append('<tr><td class="text-center" style="border: 1px solid #dee2e6;">' + vinEntry.vin + '</td></tr>');
        }
      });

      // End the table and the table-responsive div
      modalContent.append('</tbody></table></div>');
    }
  });
}

function opensignaturelink(callId) {
  $.ajax({
    url: "{{ route('dailyleads.getqoutationlink') }}",
    method: 'POST',
    data: {
      callId: callId,
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      console.log(response);
      var link = response;
      $('#linkInModal').text(link);
      $('#linkModal').modal('show');
    },
    error: function(error) {
      console.error('Error:', error);
    }
  });
}
function uploadingsignedquotation(callId) {
  $('#modalCallId').val(callId); // Set the callId
  $('#uploadingquotation').modal('show');
}
function copyLink() {
  var linkText = document.getElementById("linkInModal").textContent;
  navigator.clipboard.writeText(linkText)
    .then(function() {
      alertify.success('Link Copy successfully');
    })
    .catch(function(err) {
      console.error('Unable to copy link to clipboard', err);
    });
}
function openvins(callId) {
  $('#vinsModal').data('call-id', callId);
  $.ajax({
    url: "{{ route('dailyleads.getvinsqoutation') }}",
    method: 'POST',
    data: {
      callId: callId,
      _token: '{{ csrf_token() }}'
    },
    success: function(response) {
      console.log(response);
      displayModal(response);
      $('#vinsModal').modal('show');
    },
    error: function(error) {
      console.error('Error:', error);
    }
  });
}
function reloadDataTable(sectionId) {
    let dataTableValue;
    if (sectionId === 'dataTable2' || sectionId === 'dataTable3' || sectionId === 'dataTable4' || sectionId === 'dataTable5' || sectionId === 'dataTable6' || sectionId === 'dataTable7'|| sectionId === 'dataTable9') {
        dataTableValue = eval(sectionId);
        if (dataTableValue) {
            dataTableValue.ajax.reload();
            console.log("Current Data Table is: ", sectionId);
        } else {
            console.log(`Data table with ${sectionId} is missing`);
        }
    } else {
        console.log(`Invalid section ID: ${sectionId}`);
    }
}
function savefollowupdate() {
  var callId = $('#openfellowupdatemodel').data('call-id');
  var date = document.getElementById('date').value;
  console.log(date);
  var time = document.getElementById('time').value;
  var method = document.getElementById('method').value;
  var salesNotes = document.getElementById('sales-notesfoup').value;
  if (salesNotes === '') {
    alert('Please Write the Sales Notes');
    return;
  }
  if (date === '') {
    alert('Please select a date');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('salesNotes', salesNotes);
  formData.append('method', method);
  formData.append('time', time);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.savefollowupdate') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('FollowUp submitted successfully');
          $('#openfellowupdatemodel').modal('hide');
          reloadDataTable('dataTable9');
        }
         else {
          console.error('Error saving FollowUp');
          alert('Error saving FollowUp');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function savefollowup() {
  var callId = $('#openfellowupmodel').data('call-id');
  var date = document.getElementById('dateup').value;
  var time = document.getElementById('time').value;
  var method = document.getElementById('method').value;
  var salesNotes = document.getElementById('sales-notesfo').value;
  if (salesNotes === '') {
    alert('Please Write the Sales Notes');
    return;
  }
  if (date === '') {
    alert('Please select a date');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('salesNotes', salesNotes);
  formData.append('method', method);
  formData.append('time', time);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.savefollowup') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('FollowUp submitted successfully');
          $('#openfellowupmodel').modal('hide');
          reloadDataTable('dataTable2');
        }
         else {
          console.error('Error saving FollowUp');
          alert('Error saving FollowUp');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function saveprospecting() {
  var callId = $('#prospectingmodel').data('call-id');
  var date = document.getElementById('date').value;
  var salesNotes = document.getElementById('sales-notes').value;
  var priority = document.getElementById('priority').value;
  var fileInput = document.getElementById('screenshort').value;
  if (salesNotes === '') {
    alert('Please Write the Sales Notes');
    return;
  }
  if (date === '') {
    alert('Please select a date');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('priority', priority);
  formData.append('salesNotes', salesNotes);
  formData.append('file', document.getElementById('screenshort').files[0]);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.saveprospecting') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Prospecting submitted successfully');
          $('#prospectingmodel').modal('hide');
          reloadDataTable('dataTable2');
        }
         else {
          console.error('Error saving Prospecting');
          alert('Error saving Prospecting');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function savedemand() {
  var callId = $('#demandmodel').data('call-id');
  var date = document.getElementById('date').value;
  var salesNotes = document.getElementById('sales-notesdemands').value;
  if (date === '') {
    alert('Please select a date');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('salesNotes', salesNotes);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.savedemand') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Demand request submitted successfully');
          $('#demandmodel').modal('hide');
          reloadDataTable('dataTable3');
        } else {
          console.error('Error saving Demand');
          alert('Error saving Demand');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function saveQuotations() {
  var callId = $('#quotationModal').data('call-id');
  var date = document.getElementById('dateqoutation').value;
  var dealValue = document.getElementById('deal-value-input-qoutation').value;
  var salesNotes = document.getElementById('sales-note-qoutation').value;
  var fileInput = document.getElementById('document-upload-qoutation');
  var currencySelect = document.getElementById('currency-select');

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
  formData.append('currency', currencySelect.value);
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
        var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Quotation request submitted successfully');
          $('#quotationModal').modal('hide');
          reloadDataTable('dataTable4');

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
function savenegotiation() {
  var callId = $('#qualified').data('call-id');
  var date = document.getElementById('date-negotiation').value;
  var salesNotes = document.getElementById('sales-notes-negotiation').value;
  var dealvalues = document.getElementById('deal-value-input-negotiation').value;
  var currencySelect = document.getElementById('currency-select-negotiation');

  if (date === '') {
    alert('Please select a date');
    return;
  }
  if (dealvalues === '') {
    alert('Please Enter The Deal Value');
    return;
  }
  if (salesNotes === '') {
    alert('Please Enter the Current Notes');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('salesNotes', salesNotes);
  formData.append('dealvalues', dealvalues);
  formData.append('currency', currencySelect.value);

  var fileInput = document.getElementById('document-upload-negotiation');
  if (fileInput.files.length > 0) {
    formData.append('file', fileInput.files[0]);
  }
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.savenegotiation') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          console.log('Negotiation saved successfully');
          var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Negotiation submitted successfully');
          $('#qualified').modal('hide');
          reloadDataTable('dataTable5');

        } else {
          console.error('Error saving Negotiation');
          alert('Error saving Negotiation');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
  function saveclosed() {
  var callId = $('#ClosedModal').data('call-id');
  var date = document.getElementById('date').value;
  var salesNotes = document.getElementById('sales-notes-closed').value;
  var dealvalues = document.getElementById('deal-value-input-closed').value;
  var currencySelect = document.getElementById('currency-select-closed');
  var sonumber = document.getElementById('so_number-closed').value;
  if (date === '') {
    alert('Please select a date');
    return;
  }
  if (dealvalues === '') {
    alert('Please Enter the Correct Deal Value');
    return;
  }
  if (sonumber === '') {
    alert('Please Enter the Correct SO');
    return;
  }
  if (salesNotes === '') {
    alert('Please Enter the Sales Notes Also');
    return;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('salesNotes', salesNotes);
  formData.append('dealvalues', dealvalues);
  formData.append('currency', currencySelect.value);
  formData.append('sonumber', sonumber);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.closed') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          console.log('Sales Order saved successfully');
          var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Sales Order save successfully');
          $('#ClosedModal').modal('hide');
          reloadDataTable('dataTable6');

        } else {
          console.error('Error saving Prospecting');
          alert('Error saving Prospecting');
        }
      } else {
        console.error('Request failed with status ' + xhr.status);
        alert('Request failed. Please try again.');
      }
    }
  };
  xhr.send(formData);
}
function saveRejection() {
  var reason = document.getElementById('reason-reject').value;
  var callId = $('#rejectionModal').data('callId');
  var date = document.getElementById('date-input-reject').value;
  var reason = document.getElementById('reason-reject').value;
  var salesNotes = document.getElementById('salesnotes-reject').value;
  if(reason == "")
  {
    alert('Please give the Reason');
  }
  else
  {
  if(reason == "Others")
  {
    var reason = document.getElementById('other-reason').value;
  }
  var formData = new FormData();
  formData.append('callId', callId);
  formData.append('date', date);
  formData.append('reason', reason);
  formData.append('salesNotes', salesNotes);
  var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('sales.rejection') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          console.log('Rejection saved successfully');
          var tableRow = document.querySelector('tr[data-id="' + callId + '"]');
          if (tableRow) {
            tableRow.remove();
          }
          alertify.success('Rejection save successfully');
          $('#rejectionModal').modal('hide');
          reloadDataTable('dataTable7');


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
}
</script>
<script>
  function openWhatsApp(phoneNumber) {
    // Replace any non-digit characters to create a clean phone number
    var cleanPhoneNumber = phoneNumber.replace(/\D/g, '');

    // Form the WhatsApp URL
    var whatsappURL = 'https://api.whatsapp.com/send?phone=' + cleanPhoneNumber;

    // Open the WhatsApp chat window
    window.open(whatsappURL, '_blank');
}
</script>
<script type="text/javascript">
$(document).ready(function () {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  columnDefs: [
    { type: 'date', targets: [1] },
  ],
  order: [[0, 'desc']],
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (d === 10 || d === 11 || d === 0 ) {
        return;
      }

      var selectWrapper = $('<div class="select-wrapper"></div>');
      var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
        .appendTo(selectWrapper)
        .select2({
          width: '100%',
          dropdownCssClass: 'select2-blue'
        });
      select.on('change', function() {
        var selectedValues = $(this).val();
        column.search(selectedValues ? selectedValues.join('|') : '', true, false).draw();
      });

      selectWrapper.appendTo($(column.header()));
      $(column.header()).addClass('nowrap-td');

      column.data().unique().sort().each(function(d, j) {
        if (columnId === 3) {  // Assuming the phone column is at index 2
          var phoneNumber = $(d).text().trim();  // Extract phone number
        select.append('<option value="' + phoneNumber + '">' + phoneNumber + '</option>');
    }
    else if (columnId === 4) {  // Assuming the phone column is at index 2
          var Email = $(d).text().trim();  // Extract phone number
        select.append('<option value="' + Email + '">' + Email + '</option>');
    }
    else {
        select.append('<option value="' + d + '">' + d + '</option>');
    }
      });
    });
  }
});
$('#my-table_filter').hide();
$('#export-excel').on('click', function() {
    var filteredData = dataTable.rows({ search: 'applied' }).data();
    var data = [];
    filteredData.each(function(rowData) {
        var row = [];
        for (var i = 0; i < rowData.length; i++) {
            if (i !== 10 && i !== 11) {
              if (i === 3) {  // Assuming the phone column is at index 2
                var phoneNumber = $(rowData[i]).text().trim();  // Extract phone number
                row.push(phoneNumber);
            }
            else if (i === 4) {  // Assuming the phone column is at index 2
              var Email = $(rowData[i]).text().trim();  // Extract phone number
                row.push(Email);
            } else {
                row.push(rowData[i]);
            }
            }
        }
        data.push(row);
    });
    var excelData = [
        ['Date', 'Purchase Type', 'Customer Name', 'Customer Phone', 'Customer Email', 'Brands & Models', 'Custom Model & Brand','Preferred Language', 'Destination', 'Remarks & Messages']
    ];
    excelData = excelData.concat(data);
    var workbook = XLSX.utils.book_new();
    var worksheet = XLSX.utils.aoa_to_sheet(excelData);
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
    var blob = new Blob([s2ab(XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' }))], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Leads.xlsx';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i !== s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
    return view;
}
});
</script>
<script>
let dataTable2, dataTable3, dataTable5, dataTable6, dataTable7, dataTable9;
    $(document).ready(function () {
        dataTable2 = $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'Prospecting']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'models_brands', name: 'models_brands' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'location', name: 'location' },
                { data: 'language', name: 'language' },
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        // Set the maximum length for remarks before adding "Read More" link
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id; // Assuming you have a unique identifier for each row

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false},
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{ data: 'ddate', name: 'ddate', searchable: false },
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{
    data: 'purchaserremarks',
    name: 'purchaserremarks',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'purchaserremarks_' + row.id;

        if (row.ddate && row.ddate !== '') {
            if (data && data.length > maxLength) {
                const truncatedText = data.substring(0, maxLength);
                return `
                    <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                    <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                    <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
                `;
            } else {
                return `<span class="remarks-text">${data}</span>`;
            }
        } else {
            return '';
        }
    }
},
                {
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function (data, type, row) {
                      const bookingUrl = `{{ url('booking/create') }}/${data}`;
                      const qoutationUrl = `{{ url('/proforma_invoice/') }}/${data}`;
                      if (row.ddate && row.ddate !== '') {
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item"href="${qoutationUrl}">Quotation</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                    }
                    else
                  {
                    return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="openModalfellowup(${data})">Update FollowUp</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModald(${data})">Unique Inquiry / Demand</a></li>
                                    <li><a class="dropdown-item"href="${qoutationUrl}">Quotation</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                  }
                  }
                },
            ],
            columnDefs: [
              { type: 'date', targets: [0] }
    ]
        });
        dataTable3 = $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'New Demand']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'models_brands', name: 'models_brands' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'location', name: 'location' },
                { data: 'language', name: 'language' },
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        // Set the maximum length for remarks before adding "Read More" link
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id; // Assuming you have a unique identifier for each row

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false},
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'ddate', name: 'ddate', searchable: false },
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                {
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function (data, type, row) {
                      const bookingUrl = `{{ url('booking/create') }}/${data}`;
                      const qoutationUrl = `{{ url('/proforma_invoice/') }}/${data}`;
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"href="${qoutationUrl}">Quotation</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                    }
                },
            ]
        });
       dataTable4 = $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'Quoted']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'models_brands', name: 'models_brands' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'language', name: 'language' },
                { data: 'location', name: 'location' },
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        // Set the maximum length for remarks before adding "Read More" link
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id; // Assuming you have a unique identifier for each row

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false},
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'ddate', name: 'ddate', searchable: false },
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'qdate', name: 'qdate', searchable: false},
                { data: 'ddealvalues', name: 'ddealvalues', searchable: false },
                {
    data: 'qsalesnotes',
    name: 'qsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'qsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                {
    data: 'file_path',
    name: 'file_path',
    searchable: false,
    render: function (data, type, row) {
        if (data) {
            return `
                <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfile('${data}')"></i>
            `;
        } else {
            return '';
        }
    }
},
{
                data: 'signature_status',
                name: 'quotations.signature_status',
                render: function(data, type, row) {
                    if (data === 'Signed') {
                        return '<span class="badge badge-success">' + data + '</span>';
                    } else {
                        return '<span class="badge badge-danger">' + 'Not Signed' + '</span>';
                    }
                }
            },
            {
    data: 'id',
    name: 'id',
    render: function (data, type, row) {
        const bookingUrl = `{{ url('booking/create') }}/${data}`;
        const quotationUrlEdit = `{{ url('/proforma_invoice_edit/') }}/${data}`;
        const soUrl = `{{ url('/saleorder/') }}/${data}`;
        const preorderUrl = `{{ url('/preorder/') }}/${data}`;
        let salesOrderOption = '';
        let booking = '';
        let preorder = '';
        let signedlink = '';
        let uploadedfile = '';
        if (row.signature_status === 'Signed') {
            salesOrderOption = `<li><a class="dropdown-item" href="${soUrl}">Sales Order</a></li>`;
            preorder = `<li><a class="dropdown-item" href="${preorderUrl}">Pre Order</a></li>`;
        } else {
            signedlink = `<li><a class="dropdown-item" href="#" onclick="opensignaturelink(${data})">Signature Link</a></li>`;
            uploadedfile = `<li><a class="dropdown-item" href="#" onclick="uploadingsignedquotation(${data})">Upload Signed Quotation</a></li>`;
        }
        return `
            <div class="dropdown">
                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item"href="${quotationUrlEdit}">Update Quotation</a></li>
                    ${preorder}
                    ${salesOrderOption}
                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openvins(${data})">VINs</a></li>
                    ${signedlink}
                    ${uploadedfile}
                </ul>
            </div>`;
    }
},
            ]
        });
       dataTable5 =  $('#dtBasicExample5').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'Negotiation']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'models_brands', name: 'models_brands' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'location', name: 'location' },
                { data: 'language', name: 'language' },
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        // Set the maximum length for remarks before adding "Read More" link
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id; // Assuming you have a unique identifier for each row

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false },
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'ddate', name: 'ddate, searchable: false' },
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'qdate', name: 'qdate', searchable: false },
                { data: 'qdealvalues', name: 'qdealvalues', searchable: false },
                {
    data: 'qsalesnotes',
    name: 'qsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'qsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{
    data: 'file_path',
    name: 'file_path',
    searchable: false,
    render: function (data, type, row) {
        if (data) {
            return `
                <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfile('${data}')"></i>
            `;
        } else {
            return '';
        }
    }
},
                { data: 'ndate', name: 'ndate', searchable: false },
                { data: 'ndealvalues', name: 'ndealvalues', searchable: false },
                {
    data: 'nsalesnotes',
    name: 'nsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'nsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{
    data: 'nfile_path',
    name: 'nfile_path',
    searchable: false,
    render: function (data, type, row) {
        if (data) {
            return `
                <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfilen('${data}')"></i>
            `;
        } else {
            return '';
        }
    }
},
                {
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function (data, type, row) {
                      const bookingUrl = `{{ url('booking/create') }}/${data}`;
                      const qoutationUrlEdit = `{{ url('/proforma_invoice_edit/') }}/${data}`;
                      const soUrl = `{{ url('/saleorder/') }}/${data}`; 
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"href="${qoutationUrlEdit}">Update Qoutation</a></li>
                                <li><a class="dropdown-item"href="${soUrl}">Sales Order</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                    }
                },
            ]
        });
       dataTable6 =   $('#dtBasicExample6').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'Closed']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type' },
                { data: 'name', name: 'name' },
                // { data: 'brand', name: 'brand' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'models_brands', name: 'models_brands' },
                { data: 'custom_brand_model', name: 'custom_brand_model' },
                { data: 'location', name: 'location' },
                { data: 'language', name: 'language' },
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id;
        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false},
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'ddate', name: 'ddate', searchable: false},
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;
        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'qdate', name: 'qdate', searchable: false},
                { data: 'qdealvalues', name: 'qdealvalues', searchable: false},
                {
    data: 'qsalesnotes',
    name: 'qsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'qsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{
    data: 'file_path',
    name: 'file_path',
    searchable: false,
    render: function (data, type, row) {
        if (data) {
            return `
                <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfile('${data}')"></i>
            `;
        } else {
            return '';
        }
    }
},
//                 { data: 'ndate', name: 'ndate', searchable: false},
//                 { data: 'ndealvalues', name: 'ndealvalues', searchable: false},
//                 {
//     data: 'nsalesnotes',
//     name: 'nsalesnotes',
//     searchable: false,
//     render: function (data, type, row) {
//         const maxLength = 20;
//         const uniqueId = 'nsalesnotes_' + row.id;

//         if (data && data.length > maxLength) {
//             const truncatedText = data.substring(0, maxLength);
//             return `
//                 <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
//                 <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
//                 <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
//             `;
//         } else {
//             return `<span class="remarks-text">${data}</span>`;
//         }
//     }
// },
// {
//     data: 'nfile_path',
//     name: 'nfile_path',
//     searchable: false,
//     render: function (data, type, row) {
//         if (data) {
//             return `
//                 <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfilen('${data}')"></i>
//             `;
//         } else {
//             return '';
//         }
//     }
// },
                { data: 'cdate', name: 'cdate', searchable: false},
                { data: 'cdealvalues', name: 'ndealvalues', searchable: false},
                {
    data: 'csalesnotes',
    name: 'csalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'csalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'so_number', name: 'so_number', searchable: false},
              {
              data: 'id',
              name: 'id',
              searchable: false,
              render: function (data, type, row) {
              const updatesaleorder = `{{ url('salesorder/update') }}/${data}`;
              return `
              <a class="btn btn-sm btn-info" href="${updatesaleorder}" title="Update Sales Order">
              <i class="fa fa-window-maximize" aria-hidden="true"></i>
              </a>`;
              }
              },
            ]
        });
        dataTable7 =   $('#dtBasicExample7').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('dailyleads.index', ['status' => 'Rejected']) }}",
            columns: [
              {
            data: 'created_at',
            name: 'created_at',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
                { data: 'type', name: 'type'},
                { data: 'name', name: 'name'},
                { data: 'phone', name: 'phone'},
                { data: 'email', name: 'email'},
                { data: 'models_brands', name: 'models_brands'},
                { data: 'custom_brand_model', name: 'custom_brand_model'},
                { data: 'location', name: 'location'},
                { data: 'language', name: 'language'},
                {
    data: 'remarks',
    name: 'remarks',
    searchable: false,
    render: function (data, type, row) {
        // Set the maximum length for remarks before adding "Read More" link
        const maxLength = 20;
        const uniqueId = 'remarks_' + row.id; // Assuming you have a unique identifier for each row

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'date', name: 'date', searchable: false},
                {
    data: 'salesnotes',
    name: 'salesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'salesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'ddate', name: 'ddate', searchable: false},
                {
    data: 'dsalesnotes',
    name: 'dsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'dsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
                { data: 'qdate', name: 'qdate', searchable: false},
                { data: 'qdealvalues', name: 'qdealvalues', searchable: false},
                {
    data: 'qsalesnotes',
    name: 'qsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'qsalesnotes_' + row.id;

        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
{
    data: 'file_path',
    name: 'file_path',
    searchable: false,
    render: function (data, type, row) {
        if (data) {
            return `
                <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfile('${data}')"></i>
            `;
        } else {
            return '';
        }
    }
},
//                 { data: 'ndate', name: 'ndate', searchable: false},
//                 { data: 'ndealvalues', name: 'ndealvalues', searchable: false},
//                 {
//     data: 'nsalesnotes',
//     name: 'nsalesnotes',
//     searchable: false,
//     render: function (data, type, row) {
//         const maxLength = 20;
//         const uniqueId = 'nsalesnotes_' + row.id;

//         if (data && data.length > maxLength) {
//             const truncatedText = data.substring(0, maxLength);
//             return `
//                 <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
//                 <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
//                 <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
//             `;
//         } else {
//             return `<span class="remarks-text">${data}</span>`;
//         }
//     }
// },
// {
//     data: 'nfile_path',
//     name: 'nfile_path',
//     searchable: false,
//     render: function (data, type, row) {
//         if (data) {
//             return `
//                 <i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfilen('${data}')"></i>
//             `;
//         } else {
//             return '';
//         }
//     }
// },
                { data: 'rdate', name: 'rdate', searchable: false},
                { data: 'reason', name: 'reason', searchable: false },
                {
    data: 'rsalesnotes',
    name: 'rsalesnotes',
    searchable: false,
    render: function (data, type, row) {
        const maxLength = 20;
        const uniqueId = 'rsalesnotes_' + row.id;
        if (data && data.length > maxLength) {
            const truncatedText = data.substring(0, maxLength);
            return `
                <span class="remarks-text" id="${uniqueId}_truncated">${truncatedText}</span>
                <span class="remarks-text" id="${uniqueId}_full" style="display: none;">${data}</span>
                <a href="#" class="read-more-link" onclick="toggleRemarks('${uniqueId}')">Read More</a>
            `;
        } else {
            return `<span class="remarks-text">${data}</span>`;
        }
    }
},
            ]
        });
    dataTable8 = $('#dtBasicExample8').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('dailyleads.index', ['status' => 'Preorder']) }}",
    columns: [
        { data: 'quotationsid', name: 'quotationsid' },
        {
            data: 'date_formatted',
            name: 'date_formatted',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
        { data: 'deal_value', name: 'quotations.deal_value' },
        { data: 'sales_notes', name: 'quotations.sales_notes' },
        { data: 'model_line', name: 'master_model_lines.model_line' },
        { data: 'exterior', name: 'exterior' },
        { data: 'interior', name: 'interior' },
        { data: 'modelyear', name: 'pre_orders_items.modelyear' },
        { data: 'qty', name: 'pre_orders_items.qty' },
        { data: 'countryname', name: 'countryname' },
        { data: 'description', name: 'pre_orders_items.description' },
        { data: 'status', name: 'status' },
    ]
    });
    dataTable9 = $('#dtBasicExample9').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('dailyleads.index', ['status' => 'followup']) }}",
    columns: [
      {
            data: 'leaddate',
            name: 'leaddate',
            render: function (data, type, row) {
                if (type === 'display' || type === 'filter') {
                    // Convert the date to your desired format
                    return moment(data).format('DD-MMM-YYYY');
                }
                return data;
            }
        },
        { data: 'type', name: 'calls.type' },
        { data: 'name', name: 'calls.name' },
        { data: 'phone', name: 'calls.phone' },
        { data: 'email', name: 'calls.email' },
        { data: 'model_line', name: 'master_model_lines.model_line' },
        { data: 'language', name: 'calls.language' },
        { data: 'location', name: 'calls.location' },
        { data: 'remarks', name: 'calls.remarks' },
        { data: 'datefol', name: 'datefol' },
        { data: 'time', name: 'fellow_up.time' },
        { data: 'method', name: 'fellow_up.method' },
        { data: 'sales_notes', name: 'fellow_up.sales_notes' },
        {
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function (data, type, row) {
                      const bookingUrl = `{{ url('booking/create') }}/${data}`;
                      const qoutationUrl = `{{ url('/proforma_invoice/') }}/${data}`;
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Options">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="openModalfellowupdate(${data})">Update FollowUp</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalp(${data})">Prospecting</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModald(${data})">Unique Inquiry / Demand</a></li>
                                    <li><a class="dropdown-item"href="${qoutationUrl}">Quotation</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                  }
                },
    ]
    });
    });
    function toggleRemarks(uniqueId) {
    const $truncatedText = $('#' + uniqueId + '_truncated');
    const $fullText = $('#' + uniqueId + '_full');
    const $readMoreLink = $truncatedText.siblings('.read-more-link');
    if ($fullText.is(':hidden')) {
        $truncatedText.hide();
        $fullText.show();
        $readMoreLink.text('Read Less');
    } else {
        $truncatedText.show();
        $fullText.hide();
        $readMoreLink.text('Read More');
    }
}
document.getElementById('reason-reject').addEventListener('change', function() {
  var otherReasonInput = document.getElementById('other-reason');
  if (this.value === 'Others') {
    otherReasonInput.style.display = 'block';
  } else {
    otherReasonInput.style.display = 'none';
  }
});
</script>
<script>
function updateRemainingTime() {
    document.querySelectorAll('[id^="remaining-time-"]').forEach(function(element) {

        var assignTime = new Date(element.getAttribute('data-assign-time'));

        assignTime.setHours(assignTime.getHours() + 24);

        var currentTime = new Date();

        var diff = assignTime - currentTime;

        if (diff >= 0) {

            var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

            var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            var seconds = Math.floor((diff % (1000 * 60)) / 1000);

            element.innerHTML = `<span style="background-color: green; color: white; padding: 3px 6px; border-radius: 4px;">${hours}:${minutes}:${seconds}</span>`;

        } else {

            element.innerHTML = '';

        }

    });

}
setInterval(updateRemainingTime, 1000);
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection