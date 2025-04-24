@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .red-star {
    color: red;
    font-size: 2.2em;
}
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
  </style>
@section('content')
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<a class="btn btn-sm btn-Success float-end" href="{{ route('incident.create') }}" text-align: right>
        <i class="fa fa-car" aria-hidden="true"></i> Add New Incident
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <h4 class="card-title">
     Incident Info
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Incidents</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Pending Re-Inspection</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Vehicle Repaired</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade incidents-modal" id="incidents" tabindex="-1" aria-labelledby="incidentsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incidentsLabel">Insert Picture Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stageAndinput"></div>
                <input type="hidden" id="IncidentId" name="IncidentId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveincidentupdate()">Save</button>
            </div>
        </div>
    </div>
</div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Incident Number</th>
                  <th>Current Status</th>
                  <th>Part Purchase Remarks</th>
                  <th>Parts Purchase Order</th>
                  <th>Aging</th>
                  <th>PO Number</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Type</th>
                  <th>Narration</th>
                  <th>Reason</th>
                  <th>Driven By</th>
                  <th>Responsivity</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Incident Number</th>
                <th>Part Purchase Remarks</th>
                  <th>Parts Purchase Order</th>
                  <th>PO Number</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Type</th>
                  <th>Narration</th>
                  <th>Reason</th>
                  <th>Driven By</th>
                  <th>Responsivity</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Incident Number</th>
                  <th>PO Number</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Type</th>
                  <th>Narration</th>
                  <th>Reason</th>
                  <th>Driven By</th>
                  <th>Responsivity</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      </div>
      <div class="modal fade" id="variantDetailModal" tabindex="-1" aria-labelledby="variantDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="variantDetailModalLabel">Variant Detail</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="variantDetailModalBody" style="white-space: pre-wrap;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('incident.index', ['status' => 'Pending']) }}",
            columns: [
                { data: 'incidentsnumber', name: 'incident.id' },
                { data: 'vehicle_status', name: 'incident.vehicle_status' },
                { data: 'update_remarks', name: 'incident.update_remarks' },
                { data: 'part_po_number', name: 'incident.part_po_number' },
                { "data": "aging", "name": "aging", "searchable": false },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'created_at_pending', name: 'inspection.created_at' },
                { data: 'remark', name: 'inspection.remark' },
                { data: 'type', name: 'incident.type' },
                {
                  data: 'narration',
                  name: 'incident.narration',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Narration" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },
                { data: 'reason', name: 'incident.reason' },
                { data: 'driven_by', name: 'incident.driven_by' },
                { data: 'responsivity', name: 'incident.responsivity' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ],
            columnDefs: [
            {
                targets: 0,
                render: function (data, type, row) {
                    if (row.status === 'Re Work') {
                        return '<span class="red-star">*</span> ' + data;
                    }
                    return data;
                }
            }
        ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('incident.index', ['status' => 'Repaired']) }}",
            columns: [
                { data: 'incidentsnumber', name: 'incident.id' },
                { data: 'update_remarks', name: 'incident.update_remarks' },
                { data: 'part_po_number', name: 'incident.part_po_number' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'created_at_repaired', name: 'inspection.created_at' },
                { data: 'remark', name: 'inspection.remark' },
                { data: 'type', name: 'incident.type' },
                {
                  data: 'narration',
                  name: 'incident.narration',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Narration" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },                { data: 'reason', name: 'incident.reason' },
                { data: 'driven_by', name: 'incident.driven_by' },
                { data: 'responsivity', name: 'incident.responsivity' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('incident.index', ['status' => 'vehicles_repaired_confirmed']) }}",
            columns: [
                { data: 'incidentsnumber', name: 'incident.id' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'created_at', name: 'inspection.created_at' },
                { data: 'remark', name: 'inspection.remark' },
                { data: 'type', name: 'incident.type' },
                {
                  data: 'narration',
                  name: 'incident.narration',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Narration" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },                
                { data: 'reason', name: 'incident.reason' },
                { data: 'driven_by', name: 'incident.driven_by' },
                { data: 'responsivity', name: 'incident.responsivity' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' ,
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                }
                },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
});
    </script>
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-addon-new-selling-price');
            @endphp
            @if ($hasPermission)
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample1').DataTable();
    $('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
    var data = table1.row(this).data();
    var IncidentId = data.incidentsnumber;
    var vehicle_status = data.vehicle_status;
    var part_po_number = data.part_po_number;
    var update_remarks = data.update_remarks;
    var status = data.status;
    var vin = data.vin;
    var vehicleStatusDropdown = '<select name="vehicle_status" class="form-control">';
    vehicleStatusDropdown += '<option value="Incident Reported" ' + (vehicle_status === "Incident Reported" ? 'selected' : '') + '>Incident Reported</option>';
    vehicleStatusDropdown += '<option value="Work Started" ' + (vehicle_status === "Work Started" ? 'selected' : '') + '>Work Started</option>';
    vehicleStatusDropdown += '<option value="Work Completed" ' + (vehicle_status === "Work Completed" ? 'selected' : '') + '>Work Completed</option>';
    vehicleStatusDropdown += '</select>';
    var html = '<table class="table">';
    html += '<tr>';
    html += '<td>Part Purchase Order</td>';
    html += '<td><input type="text" name="part_po_number" class="form-control" value="' + (part_po_number || '') + '"></td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>Vehicle Status</td>';
    html += '<td>' + vehicleStatusDropdown + '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>Remarks</td>';
    html += '<td><textarea name="update_remarks" class="form-control" rows="4">' + (update_remarks || '') + '</textarea></td>';
    html += '</tr>';
    html += '</table>';
    $('#incidentsLabel').text('Update Incident Status - VIN: ' + vin);
    $('#IncidentId').val(IncidentId);
    $('#stageAndinput').html(html);
    $('#incidents').modal('show');
    if (vehicle_status === "Re Work") {
    $.ajax({
      url: '/get-incident-works/' + IncidentId,
        type: 'GET',
        success: function(data) {
            if (data.error) {
                console.error(data.error);
            } else {
              var worksHtml = '<table class="table table-bordered">';
              worksHtml += '<thead>';
              worksHtml += '<tr>';
              worksHtml += '<th>Work</th>';
              worksHtml += '<th>Status</th>';
              worksHtml += '<th>Remarks</th>';
              worksHtml += '</tr>';
              worksHtml += '</thead>';
              worksHtml += '<tbody>';
              for (var i = 0; i < data.length; i++) {
                  worksHtml += '<tr>';
                  worksHtml += '<td>' + data[i].works + '</td>';
                  worksHtml += '<td>' + data[i].status + '</td>';
                  worksHtml += '<td>' + data[i].remarks + '</td>';
                  worksHtml += '</tr>';
              }
              worksHtml += '</tbody>';
              worksHtml += '</table>';
              $('#stageAndinput').append(worksHtml);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
  }
});
});
function saveincidentupdate() {
    var IncidentId = $('#IncidentId').val();
    var part_po_number = $('input[name="part_po_number"]').val();
    var update_remarks = $('textarea[name="update_remarks"]').val();
    var vehicle_status = $('select[name="vehicle_status"]').val();
    var data = {
        IncidentId: IncidentId,
        part_po_number: part_po_number,
        update_remarks: update_remarks,
        vehicle_status: vehicle_status
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ route("incidentupdate.updatestatus") }}',
        data: data,
        success: function(response) {
            alertify.success('Variant Updated');
            $('#incidents').modal('hide'); // Hide the modal
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        },
        error: function(xhr, status, error) {
            alert('Failed to save links');
            console.error(xhr.responseText);
        }
    });
}
</script>
@endif
<script>
    $(document).ready(function () {
        var table = $('#dtBasicExample2').DataTable();
        $('#dtBasicExample2 tbody').on('dblclick', 'tr', function () {
            var data = table.row(this).data();
            var incidentId = data.incidentsnumber;
            var url = "{{ route('incident.showre', ['id' => ':id']) }}";
            url = url.replace(':id', incidentId);
            window.location.href = url;
        });
    });
</script>
<script>
  $('body').on('click', '.read-more-link', function (e) {
      e.preventDefault();
      var fullDetail = decodeURIComponent($(this).data('detail'));
      $('#variantDetailModalBody').html(fullDetail);
      $('#variantDetailModal').modal('show');
  });
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 20000);
    }
</script>
@endsection