@extends('layouts.table')
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<style>
.dataTables_wrapper .table>thead>tr>th.sorting {
  vertical-align: middle;
}
  div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.days-dropdownf {
    background: none;
    text-align: center;
    width: 50px;
    border: none;
    outline: none;
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
.contentveh {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">
     Proforma Invoice
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
    <div class="row">
    <div class="col-sm-4">
        Document Details
</div> 
<div class="col-sm-4">
Client's Details
</div> 
<div class="col-sm-4">
Delivery Details
</div> 
</div>
<hr>
    <div class="row">
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Document No : 
        </div>
        <div class="col-sm-6">
        {{$callDetails->id}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        <label for="timeRange">Document Validity:</label> 
        </div>
        <div class="col-sm-6">
        <select id="timeRange">
        <option value="1">1 day</option>
        <option value="7">7 days</option>
        <option value="14">14 days</option>
        <option value="30">30 days</option>
        <option value="60">60 days</option>
    </select>
        </div>
    </div>
    @php
        $user = Auth::user();
        $empProfile = $user->empProfile;
    @endphp
    <div class="row">
        <div class="col-sm-6">
        Sales Person : 
        </div>
        <div class="col-sm-6">
        {{ Auth::user()->name }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Office : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->office }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Email ID : 
        </div>
        <div class="col-sm-6">
        {{ Auth::user()->email }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Contact No : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->phone }}
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Customer ID : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->id }} 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Company : 
        </div>
        <div class="col-sm-6">
          <input type="text" name="company" id="company"> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        <label for="timeRange">Person :</label> 
        
        </div>
        <div class="col-sm-6">
        <input type="text" name="name" id="name" value="{{$callDetails->name}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Contact No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="phone_number" id="phone_number" value="{{$callDetails->phone}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Email : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="email" id="email" value="{{$callDetails->email}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Address : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Final Destination : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address"> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Incoterm : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Place of Delivery : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
    </div>
	<hr>
        <div class="row">
        <div class="col-sm-4">
        Payment Details
        </div> 
        <div class="col-sm-8">
        Client's Representative
        </div>  
        </div>
        <hr>
	    <div class="row">
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        System Code : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Payment Terms : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
        </div>
         </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Rep Name : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Rep No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="row">
        <div class="col-sm-6">
        CB Name : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        CB No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
    </div>
    <hr>
                    <div class="row">
            <div class="col-lg-12">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                        <tr>
                            <th>Description</th>
                            <th>Code</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Action</th>
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
        <div class="col-sm-2">
            <input type="radio" id="showVehicles" name="contentType">
            <label for="showVehicles">Add Vehicles</label>
        </div>
        <div class="col-sm-2">
            <input type="radio" id="showAccessories" name="contentType">
            <label for="showAccessories">Add Accessories</label>
        </div>
        <div class="col-sm-2">
            <input type="radio" id="showSpareParts" name="contentType">
            <label for="showSpareParts">Add Spare Parts</label>
        </div>
        <div class="col-sm-2">
            <input type="radio" id="showKits" name="contentType">
            <label for="showKits">Add Kits</label>
        </div>
        <div class="col-sm-2">
            <input type="radio" id="showLogistics" name="contentType">
            <label for="showLogistics">Add Logistics</label>
        </div>
        <div class="col-sm-2">
            <input type="radio" id="showCertificates" name="contentType">
            <label for="showCertificates">Add Certificates</label>
        </div>
    </div>
    <div id="vehiclesContent" class="contentveh">
    <hr>
    <div class="row">
    <h4 class="col-lg-2 col-md-6">Search Available Vehicles</h4>
    <div class="col-lg-12 col-md-6 d-flex align-items-end">
        <div class="col-lg-2 col-md-6">
            <label for="brand">Select Brand:</label>
            <select class="form-control col" id="brand" name="brand">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="model_line">Select Model Line:</label>
            <select class="form-control col" id="model_line" name="model_line" disabled>
                <option value="">Select Model Line</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="variant">Select Variant:</label>
            <select class="form-control col" id="variant" name="variant" disabled>
                <option value="">Select Variant</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="interior_color">Interior Color:</label>
            <select class="form-control col" id="interior_color" name="interior_color" disabled>
                <option value="">Select Interior Color</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="exterior_color">Exterior Color:</label>
            <select class="form-control col" id="exterior_color" name="exterior_color" disabled>
                <option value="">Select Exterior Color</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6 d-flex align-items-end justify-content-between">
    <div class="col">
        <button type="button" class="btn btn-primary" id="search-button">Search</button>
    </div>
    <div class="col">
        <button type="button" class="btn btn-outline-warning" id="directadding-button">Directly Adding Into Quotation</button>
    </div>
</div>
    </div>
</div>
                    <br>
                    <br>
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
                            <th>Price</th>
                            <th style="width:30px;">Add Into Qoutation</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
			</div>  
        </div>
</div>
<div id="accessoriesContent" class="contentveh">
        waqar1
    </div>
    <div id="sparePartsContent" class="contentveh">
       waqar2
    </div>
    <div id="kitsContent" class="contentveh">
        waqar3
    </div>
    <div id="logisticsContent" class="contentveh">
        waqar4
    </div>
    <div id="certificatesContent" class="contentveh">
        waqar5
    </div>
</div>
@endsection
@push('scripts')
<script>
        var radioButtons = document.querySelectorAll('input[type="radio"]');
        var contentDivs = document.querySelectorAll('.contentveh');
        radioButtons.forEach(function (radioButton, index) {
            radioButton.addEventListener("change", function () {
                contentDivs.forEach(function (contentDiv) {
                    contentDiv.style.display = "none";
                });
                if (radioButton.checked) {
                    contentDivs[index].style.display = "block";
                }
            });
        });
    </script>
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
    searching: false,
    paging: false,
    scrollY: false,
    sorting: false,
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
                return '<input type="text" class="qty-editable form-control" value="1"/>';
            }
        },
        {
            targets: -3,
            data: null,
            render: function (data, type, row) {
                return '<input type="text" class="qty-editable form-control" value=""/>';
            }
        },
        {
    targets: -6,
    data: null,
    render: function (data, type, row) {
        var brand = row[3];
        var modelDescription = row[5];
        var interiorColor = row[8];
        var exteriorColor = row[9];
        var combinedValue = brand + ', ' + modelDescription + ', ' + interiorColor + ', ' + exteriorColor;
        return '<input type="text" class="combined-value-editable form-control" value="' + combinedValue + '"/>';
    }
    },
    {
                targets: -5,
                data: null,
                render: function (data, type, row) {
                    var variant = row[6];
                    return variant;
                }
            },
            {
        targets: -4,
        data: null,
        render: function (data, type, row) {
            var price = row[10];
            return '<input type="text" class="price-editable form-control" value="' + price + '"/>';
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
                        vehicle.price,
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
                        { title: 'Price' },
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
            url: '{{ route('booking.checkingso') }}',
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