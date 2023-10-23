@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
<style>
.dataTables_wrapper .table>thead>tr>th.sorting {
  vertical-align: middle;
}
  div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.days-dropdownf {
    background: none; /* Remove background */
    text-align: center; /* Center text horizontally */
    width: 50px; /* Adjust the width as needed */
    border: none; /* Remove border if desired */
    outline: none; /* Remove outline on focus if desired */
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.circle-button {
    display: inline-block;
    width: 20px;
    height: 20px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    line-height: 20px;
    border-radius: 50%;
    border: 1px solid #FFFF00; 
    cursor: pointer;
    transition: background-color 0.3s ease; 
}
.circle-button::before {
    content: '+';
    font-size: 16px;
}
.circle-buttonr {
    display: inline-block;
    width: 22px;
    height: 22px;
    background-color: #Ff0000;
    color: white;
    text-align: center;
    line-height: 20px;
    border-radius: 30%;
    border: 1px solid #FFFF00; 
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.circle-buttonr::before {
    content: 'X';
    font-size: 16px;
}
.circle-button:hover {
    background-color: #45a049;
    border: 2px solid #FFFFFF;
}
    </style>
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4>Booking New Vehicles</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="col-lg-12">
            <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>  
                <div class="row"> 
					<div class="col-lg-2 col-md-4">
                        <label for="basicpill-firstname-input" class="form-label">Date : </label>
                        <input type="date" name="date" id="name" class="form-control" value="{{ date('Y-m-d') }}">
                        <input type="hidden" name="call_id" id="call_id" class="form-control" value="{{ $call_id }}">
                    </div>
                    </div>
                    <br>
                    <hr>
                    <h4>Selected Vehicles for Booking</h4>
                    <div class="card-body">
                    <div class="row">
            <div class="col-lg-12">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>VIN</th>
                            <th>Brand Name</th>
                            <th>Model Line</th>
                            <th>Model Details</th>
                            <th>Variant Name</th>
                            <th>Variant Detail</th>
                            <th>Interior Color</th>
                            <th>Exterior Color</th>
                            <th style="width:30px;">Days</th>
                            <th style="width:30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
			</div> 
        </div>
        <br>
        <div class="row">
        <div class="col-lg-2">
            <label for="etd">ETD (Estimated Time of Delivery)</label>
            <input type="date" id="etd" name="etd" class="form-control">
        </div>
        <div class="col-lg-10">
            <label for="remarks">Booking Notes</label>
            <input type="text" id="bookingnotes" name="bookingnotes" class="form-control">
        </div>
    </div>
</br>
        <div class="col-lg-12 col-md-12">
    <input type="submit" id="submit-button" name="submit" value="Submit" style="float: right;" class="btn btn-success" />
</div> 
<br>
    </div>
                    <hr>
                    <div class="row"> 
                    <h4>Search Available Vehicles</h4>
                    <br>
                    <br>
                    <div class="col-lg-2 col-md-6">
                    <label for="brand">Select Brand:</label>
            <select class="form-control" id="brand" name="brand">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="model_line">Select Model Line:</label>
            <select class="form-control" id="model_line" name="model_line" disabled>
                <option value="">Select Model Line</option>
            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="variant">Select Variant:</label>
            <select class="form-control" id="variant" name="variant" disabled>
                <option value="">Select Variant</option>
            </select>
            </div>
            <div class="col-lg-2 col-md-6">
            <label for="interior_color">Select Interior Color:</label>
    <select class="form-control" id="interior_color" name="interior_color" disabled>
        <option value="">Select Interior Color</option>
    </select>
            </div>
            <div class="col-lg-2 col-md-6">
            <label for="exterior_color">Select Exterior Color:</label>
    <select class="form-control" id="exterior_color" name="exterior_color" disabled>
        <option value="">Select Exterior Color</option>
    </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex align-items-end">
        <button type="button" class="btn btn-primary" id="search-button">Search</button>
    </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
            <div class="col-lg-12">
            <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>VIN</th>
                            <th>Brand Name</th>
                            <th>Model Line</th>
                            <th>Model Details</th>
                            <th>Variant Name</th>
                            <th>Variant Detail</th>
                            <th>Interior Color</th>
                            <th>Exterior Color</th>
                            <th style="width:30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
			</div>  
        </div>
    </div>
		</br>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
<script>
        $(document).ready(function() {
            $('#brand').select2();
            $('#model_line').select2();
            $('#variant').select2();
            $('#interior_color').select2();
            $('#exterior_color').select2();
            $('#brand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                $('#model_line').prop('disabled', false);
                $('#model_line').empty().append('<option value="">Select Model Line</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getmodel', ['brandId' => '__brandId__']) }}'
                        .replace('__brandId__', brandId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#model_line').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#model_line').prop('disabled', true);
                $('#variant').prop('disabled', true);
                $('#model_line').empty().append('<option value="">Select Model Line</option>');
                $('#variant').empty().append('<option value="">Select Variant</option>');
            }
        });
        $('#model_line').on('change', function() {
            var modelLineId = $(this).val();
            if (modelLineId) {
                $('#variant').prop('disabled', false);
                $('#variant').empty().append('<option value="">Select Variant</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getvariant', ['modelLineId' => '__modelLineId__']) }}'
                        .replace('__modelLineId__', modelLineId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#variant').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#variant').prop('disabled', true);
                $('#variant').empty().append('<option value="">Select Variant</option>');
            }
        });
        $('#variant').on('change', function() {
            var variantId = $(this).val();
            if (variantId) {
                $('#interior_color').prop('disabled', false);
                $('#exterior_color').prop('disabled', false);
                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getInteriorColors', ['variantId' => '__variantId__']) }}'
                        .replace('__variantId__', variantId),
                    success: function(response) {
                        $('#interior_color').empty().append('<option value="">Select Interior Color</option>');
                        $.each(response, function(key, value) {
                            $('#interior_color').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getExteriorColors', ['variantId' => '__variantId__']) }}'
                        .replace('__variantId__', variantId),
                    success: function(response) {
                        $('#exterior_color').empty().append('<option value="">Select Exterior Color</option>');
                        $.each(response, function(key, value) {
                            $('#exterior_color').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#interior_color').prop('disabled', true);
                $('#exterior_color').prop('disabled', true);
                $('#interior_color').empty().append('<option value="">Select Interior Color</option>');
                $('#exterior_color').empty().append('<option value="">Select Exterior Color</option>');
            }
        });
var secondTable = $('#dtBasicExample2').DataTable({
    columnDefs: [
        {
            targets: -1,
            data: null,
            defaultContent: '<button class="circle-buttonr remove-button">Remove</button>'
        },
        {
            targets: -2,
            data: null,
            render: function (data, type, row) {
                var callId = $('#call_id').val();
                var isEditable = checkIfRowIsEditable(callId);
                var options = '';
                if (isEditable) {
                    for (var i = 1; i <= 15; i++) {
                        options += '<option value="' + i + '">' + i + '</option>';
                    }
                    return '<select class="days-dropdown">' + options + '</select>';
                } else {
                    return '<input type="text" class="days-dropdown" value="3" readonly>';
                }
            }
        }
    ]
});
$('#dtBasicExample2 tbody').on('click', '.remove-button', function() {
    var row = secondTable.row($(this).parents('tr'));
    var rowData = row.data();
    var vehicleIdToRemove = rowData[0];
    moveRowToFirstTable(vehicleIdToRemove);
});
function moveRowToFirstTable(vehicleId) {
    var firstTable = $('#dtBasicExample1').DataTable();
    var secondTable = $('#dtBasicExample2').DataTable();
    var secondTableRow = secondTable.rows().indexes().filter(function(value, index) {
        return secondTable.cell(value, 0).data() == vehicleId;
    });

    if (secondTableRow.length > 0) {
        var rowData = secondTable.row(secondTableRow).data();
        firstTable.row.add(rowData).draw();
        secondTable.row(secondTableRow).remove().draw();
    }
}
$('#submit-button').on('click', function() {
    var selectedData = [];
secondTable.rows().every(function() {
    var data = this.data();
    var vehicleId = data[0];
    var selectedDays = $(this.node()).find('.days-dropdown').val();

    selectedData.push({ vehicleId: vehicleId, days: selectedDays });
});
var dateValue = $('#name').val();
var callIdValue = $('#call_id').val();
var etd = $('#etd').val();
var bookingnotes = $('#bookingnotes').val();
var requestData = {
    selectedData: JSON.stringify(selectedData),
    date: dateValue,
    call_id: callIdValue,
    bookingnotes: bookingnotes,
    etd: etd
};
console.log(requestData);
var csrfToken = $('meta[name="csrf-token"]').attr('content');
$.ajax({
    type: 'POST',
    url: '{{ route('booking.store') }}',
    data: requestData,
    headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
        alertify.success('Booking request submitted successfully');
        setTimeout(function() {
            window.location.href = '{{ route('dailyleads.index') }}';
        }, 1000);
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
});
});
$(document).on('click', '.remove-button', function() {
    var row = $(this).closest('tr');
    var rowData = [];
    row.find('td').each(function() {
        rowData.push($(this).text());
    });
    moveRowToFirstTable(rowData);
});

$(document).on('click', '.add-button', function() {
    var vehicleId = $(this).data('vehicle-id');
    console.log('Add button clicked for vehicle ID:', vehicleId);
    var rowData = [];
    var row = $(this).closest('tr');
    row.find('td').each(function() {
        rowData.push($(this).text());
    });
    var secondTable = $('#dtBasicExample2').DataTable();
    secondTable.row.add(rowData).draw();
    var firstTable = $('#dtBasicExample1').DataTable();
    firstTable.row(row).remove().draw();
});
$('#search-button').on('click', function() {
    var variantId = $('#variant').val();
    var interiorColorId = $('#interior_color').val();
    var exteriorColorId = $('#exterior_color').val();
    if (!variantId) {
        alert("Please select a variant before searching.");
        return;
    }
    var url = '{{ route('booking.getbookingvehicles', [':variantId', ':interiorColorId?', ':exteriorColorId?']) }}';
    url = url.replace(':variantId', variantId);
    if (interiorColorId) {
        url = url.replace(':interiorColorId', interiorColorId);
    } else {
        url = url.replace(':interiorColorId?', '');
    }

    if (exteriorColorId) {
        url = url.replace(':exteriorColorId', exteriorColorId);
    } else {
        url = url.replace(':exteriorColorId?', '');
    }

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            var data = response.map(function(vehicle) {
                var addButton = '<button class="add-button" data-vehicle-id="' + vehicle.id + '">Add</button>';
                return [
                    vehicle.id,
                    vehicle.grn_status,
                    vehicle.vin,
                    vehicle.brand,
                    vehicle.model_line,
                    vehicle.model_detail,
                    vehicle.variant_name,
                    vehicle.variant_detail,
                    vehicle.interior_color,
                    vehicle.exterior_color,
                    addButton
                ];
            });
            if ($.fn.dataTable.isDataTable('#dtBasicExample1')) {
                $('#dtBasicExample1').DataTable().destroy();
            }
            $('#dtBasicExample1').DataTable({
                data: data,
                columns: [
                    { title: 'ID' },
                    { title: 'Status' },
                    { title: 'VIN' },
                    { title: 'Brand Name' },
                    { title: 'Model Line' },
                    { title: 'Model Detail' },
                    { title: 'Variant Name' },
                    { title: 'Variant Detail' },
                    { title: 'Interior Color' },
                    { title: 'Exterior Color' },
                    {
                        title: 'Actions',
                        render: function(data, type, row) {
                            return '<div class="circle-button add-button" data-vehicle-id="' + row[0] + '"></div>';
                        }
                    }
                ]
            });
        }
    });
});
function checkIfRowIsEditable(callId) {
    var isEditable = false;
    $.ajax({
        type: 'GET',
        url: '{{ route('booking.checkingso') }}', // Use the named route
        data: { call_id: callId},
        async: false,
        success: function (response) {
            isEditable = response.editable;
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

    return isEditable;
}
    });
    </script>
@endpush