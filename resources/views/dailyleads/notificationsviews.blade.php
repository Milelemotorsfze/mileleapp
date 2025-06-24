@extends('layouts.table')
<style>|
.badge-success {
    background-color: #28a745; /* Green color for 'Signed' */
    color: #fff;
}
.badge-danger {
    background-color: #dc3545; /* Red color for 'Unsigned' */
    color: #fff;
}
    </style>
@section('content')
<div class="card-header">
        <h4 class="card-title">View Notifications</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
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
        <div class="table-responsive" >
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Priority</th>
                  <th>Lead Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                @if($additionalValue == "Pending Lead")
                  <th>Action</th>
                  @elseif($additionalValue == "Fellow Up")
                  <th>Followup Date</th>
                  <th>Followup Time</th>
                  <th>Method</th>
                  <th>Sales Notes</th>
                  <th>Action</th>
                  @elseif($additionalValue == "Quotation Fellow Up")
                  <th>Qoutation Date</th>
                  <th>Deal Value</th>
                  <th>Qoutation Notes</th>
                  <th>View Qoutation</th>
                  <th>Signature Status</th>
                  <th>Action</th>
                  @else
                  <th>Prospectings Date</th>
                  <th>Prospectings Notes</th>
                  <th>Action</th>
                @endif
                </tr>
                </thead>
                <tbody>
                    <tr data-id="{{$call_id}}">
                    @php
                        $priority = strtolower(trim($calls->priority ?? ''));
                    @endphp

                    <td>
                        @if ($priority === 'hot' || $priority === 'high')
                            <i class="fas fa-circle blink" style="color: red;"> Hot</i>
                        @elseif ($priority === 'normal')
                            <i class="fas fa-circle" style="color: green;"> Normal</i>
                        @elseif ($priority === 'low')
                            <i class="fas fa-circle" style="color: orange;"> Low</i>
                        @else
                            <i class="fas fa-circle" style="color: black;"> Regular</i>
                        @endif
                    </td>
                    <td>{{ date('d-M-Y', strtotime($calls->created_at)) }}</td>
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
                    @if($additionalValue == "Pending Lead")
                                    <td>
                                    <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="openModalfellowup('{{ $call_id }}')">FollowUp</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModalp('{{ $call_id }}')">Prospecting</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModald('{{ $call_id }}')">Unique Inquiry / Demand</a></li>
                    <li><a class="dropdown-item"href="{{route('qoutation.proforma_invoice',['callId'=> $call_id]) }}">Quotation</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $call_id }}')">Rejected</a></li>
                    </ul>
                    </div>
                    </td>
                    @elseif($additionalValue == "Fellow Up")
                    <td>{{ date('d-M-Y', strtotime($calls->date)) }}</td>
                    <td>{{ date('H:i:s', strtotime($calls->time)) }}</td>
                    <td>{{$calls->method}}</td>
                    <td>{{$calls->sales_notes}}</td>
                    <td>
                    <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="openModalfellowupdate('{{ $call_id }}')">Update FollowUp</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModalp('{{ $call_id }}')">Prospecting</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModald('{{ $call_id }}')">Unique Inquiry / Demand</a></li>
                    <li><a class="dropdown-item"href="{{route('qoutation.proforma_invoice',['callId'=> $call_id]) }}">Quotation</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $call_id }}')">Rejected</a></li>
                    </ul>
                    </div>
                    </td>
                    @elseif($additionalValue == "Quotation Fellow Up")
                    <td>{{ date('d-M-Y', strtotime($calls->date)) }}</td>
                    <td>{{ $calls->deal_value}}</td>
                    <td>{{$calls->sales_notes}}</td>
                    <td><a href="#" class="fas fa-file-alt view-file" onclick="openModalfile('${calls->file_path}')" style="cursor: pointer;"></a></td>
                    <td>
                    @if ($calls->signature_status === 'Signed') 
                    <span class="badge badge-success">Signed</span>
                    @else
                    <span class="badge badge-danger">Not Signed</span>
                    @endif
                    </td>
                    <td>
                    <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-content">
                        @if($calls->signature_status === 'Signed')
                        <a class="dropdown-item" href="{{ url('preorder') }}/{{ $call_id }}">Pre Order</a></li>
                        <a class="dropdown-item" href="{{ url('saleorder') }}/{{ $call_id }}">Sales Order</a>
                        @else
                        <a class="dropdown-item" href="{{ url('proforma_invoice_edit') }}/{{ $call_id }}">Update Quotation</a>
                        <a class="dropdown-item" href="#" onclick="opensignaturelink({{ $call_id }})">Signature Link</a>
                        @endif
                        <a class="dropdown-item" href="#" onclick="openModalr({{ $call_id }})">Rejected</a>
                    </div>
                    </div>
                    </ul>
                    </div>
                    </td>
                    @else
                    <td>{{ date('d-M-Y', strtotime($calls->date)) }}</td>
                    <td>{{$calls->sales_notes}}</td>
                    <td>
                                    <div class="dropdown">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="openModalfellowup('{{ $call_id }}')">FollowUp</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModald('{{ $call_id }}')">Unique Inquiry / Demand</a></li>
                    <li><a class="dropdown-item"href="{{route('qoutation.proforma_invoice',['callId'=> $call_id]) }}">Quotation</a></li>
                    <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $call_id }}')">Rejected</a></li>
                    </ul>
                    </div>
                    </td>
                    @endif
                  </tr>
                        </tbody>
            </table>
        </div>
		</br>
    </div>
    @endsection
@push('scripts')
<script>
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
        url: "{{ url('/update-followup-info-data/') }}/" + callId,
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
@endpush