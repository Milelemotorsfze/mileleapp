@extends('layouts.table')
<style>
    .row {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .label {
        flex-basis: 30%;
    }

    .value {
        flex-basis: 70%;
        background-color: #f2f2f2;
        border-radius: 3px;
        padding: 5px;
        font-weight: bold;
        color: #333;
    }
</style>
@section('content')
<div class="card-header">
        <h4 class="card-title">Vehicle Detail</h4>
        @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $previousId) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Vehicles No: {{$currentId}}</b>
@if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $nextId) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
    @php
    $po = DB::table('purchasing_order')->where('id', $vehicle->purchasing_order_id)->first();
    $po_date = $po->po_date ?: '';
    $po_number = $po->po_number ?: '';
    $grn = $vehicle->grn_id ? DB::table('grn')->where('id', $vehicle->grn_id)->first() : null;
    $grn_date = $grn ? $grn->date : null;
    $grn_number = $grn ? $grn->grn_number : null;
    $gdn = $vehicle->gdn_id ? DB::table('gdn')->where('id', $vehicle->gdn_id)->first() : null;
    $gdn_date = $gdn ? $gdn->date : null;
    $gdn_number = $gdn ? $gdn->gdn_number : null;
    use Carbon\Carbon;
    if($po_date){
    $po_date = Carbon::createFromFormat('Y-m-d', $po_date)->format('d-M-Y');
    }
    if($grn_date){
    $grn_date = Carbon::createFromFormat('Y-m-d', $grn_date)->format('d-M-Y');
    }
    if($gdn_date){
    $gdn_date = Carbon::createFromFormat('Y-m-d', $gdn_date)->format('d-M-Y');
    }
    @endphp
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
            <strong>PO Number:</strong></strong>
        </div> 
        <div class="col-lg-8 value">
            {{$po_number}}
        </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>PO Date:</strong> 
            </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Estimated Arrival:</strong> 
            </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> ETA Timer:</strong>  
        </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Stock Status:</strong>
            </div> 
        <div class="col-lg-8 value">
             {{$po_date}}
             </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>GRN Number:</strong> 
            </div> 
        <div class="col-lg-8 value">
            {{$grn_number}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>GRN Date:</strong>
        </div> 
        <div class="col-lg-8 value"> 
            {{$grn_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Inspection Date:</strong>
        </div> 
        <div class="col-lg-8 value">  
            {{$po_date}}
        </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Aging:</strong> 
        </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>SO Number:</strong> 
        </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Sales Person:</strong> 
        </div> 
        <div class="col-lg-8 value">
            {{$po_date}}
            </div>
        </div>
    </div>
    <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Reservation Date:</strong>
            </div> 
        <div class="col-lg-8 value">
             {{$po_date}}
             </div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Due Date:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}
        </div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>GDN Number:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$gdn_number}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>GDN Date:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$gdn_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Brand:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Model Line:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Model Des:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Variant:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>  Variant Details:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$po_date}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> VIN Number:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Conversion:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Engine:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Model Year:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Steering:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Seats:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Fuel Type:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Transmission:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Exterior Colour:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Interior Colour:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Upholstery:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Production Year:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Allowed Territory:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Warehouse:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Price:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong>Import Doc Type:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Doc Owership:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> Doc With:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
   <div class="col-lg-3">
    <div class="row">
        <div class="col-lg-4 label">
        <strong> BL Number:</strong>
             </div> 
        <div class="col-lg-8 value"> 
            {{$vehicle->vin}}</div>
        </div>
    </div>
</div>
<hr>
<h4 class="card-title">Changes Log Details</h4>
<hr>
<div class="table-responsive" >
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Updated By</th>
                <th>Role</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mergedLogs as $vehiclesLog)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($vehiclesLog->date)) }}</td>
                <td>{{ $vehiclesLog->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $vehiclesLog->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $vehiclesLog->field }}</td>
                <td>{{ $vehiclesLog->old_value }}</td>
                <td>{{ $vehiclesLog->new_value }}</td>
                <td>{{ $vehiclesLog->new_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
    <script>
$(document).ready(function() {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (columnName === "statuss") {
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
        select.append('<option value="' + d + '">' + d + '</option>');
      });
    });
  }
});
  $('.dataTables_filter input').on('keyup', function() {
    dataTable.search(this.value).draw();
  });
});
    </script>
        <script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
    <script>
  function confirmCancel() {
    var confirmDialog = confirm("Are you sure you want to cancel this purchase order?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
    @endsection
@push('scripts')
@endpush
