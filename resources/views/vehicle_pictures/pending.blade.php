@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
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
.custom-badge {
  display: inline-block;
  padding: 2px 2px;
  font-size: 12px;
  font-weight: bold;
  border-radius: 4px;
  margin: 4px 4px 0 0;
  background-color: rgba(0, 123, 255, 0.8);
  color: #fff;
}

.custom-badge.primary {
  background-color: rgba(60, 60, 60, 0.8);
}

.custom-badge.info {
  background-color: rgba(23, 162, 184, 0.8);
}

.custom-badge.danger {
  background-color: rgba(220, 53, 69, 0.8);
}

.custom-badge.success {
  background-color: rgba(40, 167, 69, 0.8);
}

.custom-badge.warning {
  background-color: rgba(255, 193, 7, 0.8);
}
.custom-badge.dark {
  background-color: rgba(0, 0, 255, 0.8);
}
  </style>
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-edit');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <h4 class="card-title">
     Inspection Pictures Info
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Pictures</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">All Pictures</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade pictures-modal" id="pictures" tabindex="-1" aria-labelledby="picturesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="picturesLabel">Insert Picture Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stageAndinput"></div>
                <input type="hidden" id="vehicleId" name="vehicleId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="savePictureLink()">Save Link</button>
            </div>
        </div>
    </div>
</div>
  <div class="modal fade allpictures-modal" id="allpictures" tabindex="-1" aria-labelledby="allpicturesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="allpicturesLabel">View Pictures Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                <div id="stageAndLinks"></div>
            </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                  <th>Inspection Date</th>
                  <th>Pending Pictures</th>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>SO Number</th>
                  <th>VIN</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
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
                <th>Inspection Date</th>
                  <th>Available Pictures</th>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>SO Number</th>
                  <th>VIN</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
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
    </div>
    <div class="modal fade" id="variantDetailModal" tabindex="-1" aria-labelledby="variantDetailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="variantDetailModalLabel">Full Content</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="variantDetailModalBody" style="white-space: pre-wrap;"></div>
        </div>
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicle_pictures.pending', ['status' => 'Pending']) }}",
            columns: [
                { data: 'created_at_formatted', name: 'inspection.created_at' },
                { data: 'stages', name: 'inspection.stage' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                {
                    data: 'grn_number',
                    name: 'movement_grns.grn_number',
                    render: function(data, type, row) {
                        if (row.inspection_status == 'Approved') {
                          
                            return data;
                        }
                        return ''; 
                    }
                },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('vehicle_pictures.pending', ['status' => 'Submitted']) }}",
            columns: [
                { data: 'created_at_formatted', name: 'inspection.created_at' },
                { data: 'stages', name: 'inspection.stage' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                {
                    data: 'grn_number',
                    name: 'movement_grns.grn_number',
                    render: function(data, type, row) {
                        if (row.inspection_status == 'Approved') {
                          
                            return data;
                        }
                        return ''; 
                    }
                },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 10) {
                        let shortText = words.slice(0, 10).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return data;
                  }
                },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ],
            drawCallback: function(settings) {
        var api = this.api();
        // console.log(api.rows().data().toArray());
    }
        });
});
    </script>
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample1').DataTable();
$('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
    var data = table1.row(this).data();
    var vehicleId = data.vehicle_id;
    var stages = data.stages;
    var vin = data.vin;
    var stagesArray = stages.split(', ');
    var html = '<table class="table">';
    for (var i = 0; i < stagesArray.length; i++) {
        html += '<tr>';
        html += '<td>' + stagesArray[i] + '</td>';
        html += '<td><input type="text" name="link" class="form-control" value="">';
        html += '</tr>';
    }
    html += '</table>';

    $('#picturesLabel').text('View Pictures Details - VIN: ' + vin);
    $('#vehicleId').text(vehicleId);
    $('#stageAndinput').html(html);
    $('#pictures').modal('show');
});
    var table2 = $('#dtBasicExample2').DataTable();
    $('#dtBasicExample2 tbody').on('dblclick', 'tr', function () {
    var data = table2.row(this).data();
    var vehicleId = data.vehicle_id;
    var stages = data.stages;
    var vin = data.vin;
    var links = data.links;
    var stagesArray = stages.split(', ');
    var linksArray = links.split(', ');
    var html = '<table class="table">';
    for (var i = 0; i < stagesArray.length; i++) {
        html += '<tr>';
        html += '<td>' + stagesArray[i] + '</td>';
        html += '<td><a href="' + linksArray[i] + '" target="_blank" class="btn btn-primary">Open</a></td>';
        html += '</tr>';
    }
    html += '</table>';

    $('#allpicturesLabel').text('View Pictures Details - VIN: ' + vin);
    $('#stageAndLinks').html(html);
    $('#allpictures').modal('show');
});
});
function savePictureLink() {
    var vehicleId = $('#vehicleId').text();
    var links = [];
    var stages = [];
    $('#stageAndinput input[name="link"]').each(function () {
        links.push($(this).val());
    });

    $('#stageAndinput td:first-child').each(function () {
        stages.push($(this).text());
    });
    var data = {
        vehicleId: vehicleId,
        links: links.map(function (link, index) {
            return {
                stage: stages[index],
                link: link
            };
        })
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    $.ajax({
        type: 'POST',
        url: '{{ route("vehicle_pictures.saving") }}', 
        data: data,
        success: function(response) {
            alertify.success('Variant Updated');
			$('#allpictures').modal('hide');
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        },
        error: function (xhr, status, error) {
            alert('Failed to save links');
            console.error(xhr.responseText);
        }
    });
}
</script>
<script>
  $('body').on('click', '.read-more-link', function (e) {
    e.preventDefault();
    const fullDetail = decodeURIComponent($(this).data('detail'));
    const title = $(this).data('title') || 'Full Detail';
    $('#variantDetailModalLabel').text(title);
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
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection