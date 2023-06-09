@extends('layouts.table')
<style>
#table-responsive {
  height: 100vh; /* Set the container height to match the screen height */
  overflow-y: auto; /* Enable vertical scrolling */
}

#dtBasicSupplierInventory {
  width: 100%; /* Optionally set the table width to 100% */
  font-size: 14px; /* Adjust the font size as needed */
}
.nowrap-td {
    white-space: nowrap;
  }
  /* Additional styles for Select2 dropdown */
.select2-container .select2-selection--single {
  height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 34px;
  right: 6px;
  top: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 5px 5px 0 5px;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__clear {
  line-height: 34px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  background-color: #f8f9fc;
  border-color: #ddd;
  border-radius: 0;
  transition: background-color 0.2s, border-color 0.2s;
}

.select2-container--default .select2-selection--single .select2-selection__arrow:hover {
  background-color: #e9ecef;
  border-color: #bbb;
}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Stock Report
        </h4>
    </div>
    <div class="card-body">
    @if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    @can('view-po-details')
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                    <th class="nowrap-td">PO Date</th>
                    <th class="nowrap-td">PO Number</th>
                    <th class="nowrap-td">Estimated Arrival</th>
                    <th class="nowrap-td">GRN</th>
                    <th class="nowrap-td">GRN Date</th>
                    <th class="nowrap-td">Aging</th>
                    <th class="nowrap-td">SO</th>
                    <th class="nowrap-td">SO Date</th>
                    <th class="nowrap-td">Sales Person</th>
                    <th class="nowrap-td">Booking</th>
                    <th class="nowrap-td">GDN</th>
                    <th class="nowrap-td">GDN Date</th>
                    <th class="nowrap-td">Remarks</th>
                    <th class="nowrap-td">Conversion</th>
                    <th class="nowrap-td">Variant</th>
                    <th class="nowrap-td">Variant Detail</th>
                    <th class="nowrap-td">Brand</th>
                    <th class="nowrap-td">Model Line</th>
                    <th class="nowrap-td">Model Description</th>
                    <th class="nowrap-td">VIN</th>
                    <th class="nowrap-td">Engine</th>
                    <th class="nowrap-td">MY</th>
                    <th class="nowrap-td">Steering</th>
                    <th class="nowrap-td">Seats</th>
                    <th class="nowrap-td">Fuel</th>
                    <th class="nowrap-td">Gear</th>
                    <th class="nowrap-td">Ex Colour</th>
                    <th class="nowrap-td">Int Colour</th>
                    <th class="nowrap-td">Upholestry</th>
                    <th class="nowrap-td">PY MM YYYY</th>
                    <th class="nowrap-td">Warehouse</th>
                    <th class="nowrap-td">Price</th>
                    <th class="nowrap-td">Territory</th>
                    <th class="nowrap-td">Import Document Type</th>
                    <th class="nowrap-td">Document Ownership</th>
                    <th class="nowrap-td">Documents With</th>
                    <th class="nowrap-td">DUCAMZ IN/OUT</th>
                    <th class="nowrap-td">BL</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $vehicles)
                    <tr data-id="{{$vehicles->id}}">
                    @php
                     $po_date = "";
                     $po_number = "";
                     $name = "";
                     $grn_date = "";
                     $grn_number = "";
                     $gdn_date = "";
                     $gdn_number = "";
                     $aging = "";
                     $salesname = "";
                     $booking_name = "";
                     $conversions = "";
                     $varaints_name = "";
                     $varaints_detail = "";
                     $brand_name = "";
                     $model_line = "";
                     $po = DB::table('purchasing_order')->where('id', $vehicles->purchasing_order_id)->first();
                     $po_date = $po->po_date;
                     $po_number = $po->po_number;
                     $variants = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                     $name = $variants->name;
                     $grn = $vehicles->grn_id ? DB::table('grn')->where('id', $vehicles->grn_id)->first() : null;
                     $grn_date = $grn ? $grn->date : null;
                     $grn_number = $grn ? $grn->grn_number : null;
                     $gdn = $vehicles->gdn_id ? DB::table('gdn')->where('id', $vehicles->gdn_id)->first() : null;
                     $gdn_date = $gdn ? $gdn->date : null;
                     $gdn_number = $gdn ? $gdn->gdn_number : null;
                     $so = $vehicles->so_id ? DB::table('so')->where('id', $vehicles->so_id)->first() : null;
                    $so_date = $so ? $so->so_date : null;
                    $so_number = $so ? $so->so_number : null;
                    $sales_person_id = $so ? $so->sales_person_id : null;
                    $sales_person = $sales_person_id ? DB::table('users')->where('id', $sales_person_id)->first() : null;
                    $salesname = $sales_person ? $sales_person->name : null;
                    $booking = $vehicles->booking_id ? DB::table('booking')->where('id', $vehicles->booking_id)->first() : null;
                    $booking_name = $booking ? $booking->name : null;
                    $conversion = $vehicles->conversion_id ? DB::table('conversion')->where('id', $vehicles->conversion_id)->first() : null;
                    $conversions = $conversion ? $conversion->id : null;
                     $result = DB::table('varaints')
                                ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                                ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                                ->where('varaints.id', $vehicles->varaints_id)
                                ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
                                ->first();
                                $varaints_name = $result->name;
                                $varaints_my = $result->my;
                                $varaints_steering = $result->steering;
                                $varaints_fuel_type = $result->fuel_type;
                                $varaints_seat = $result->seat;
                                $varaints_detail = $result->detail;
                                $varaints_gearbox = $result->gearbox;
                                $varaints_upholestry = $result->upholestry;
                                $brand_name = $result->brand_name;
                                $model_line = $result->model_line;
                                $documents = $vehicles->documents_id ? DB::table('documents')->where('id', $vehicles->documents_id)->first() : null;
                                $import_type = $documents ? $documents->import_type : null;
                    $owership = $documents ? $documents->owership : null;
                    $document_with = $documents ? $documents->document_with : null;
                    $bl = $vehicles->bl_id ? DB::table('bl')->where('id', $vehicles->bl_id)->first() : null;
                        $bl_number = $bl ? $bl->bl_number : null;
                     @endphp
                     <td class="nowrap-td">{{ $po_date }}</td>
                     <td class="nowrap-td">{{ $po_number }}</td>
                     <td class="nowrap-td"><input type="text" name="po_date" value="{{ $po_number }}" data-original-value="{{ $po_number }}"></td>
                     <td class="nowrap-td"><input type="text" name="grn_number" value="{{ $grn_number }}"></td>
                     <td class="nowrap-td"><input type="date" name="grn_date" value="{{ $grn_date }}"></td>
                     <td class="nowrap-td">{{ $aging }}</td>
                     <td class="nowrap-td">{{ $so_number }}</td>
                     <td class="nowrap-td">{{ $so_date}}</td>
                     <td class="nowrap-td">{{ $salesname }}</td>
                     <td class="nowrap-td">{{ $booking_name }}</td>
                     <td class="nowrap-td"><input type="text" name="gdn_number" value="{{ $gdn_number }}"></td>
                     <td class="nowrap-td"><input type="date" name="gdn_date" value="{{ $gdn_date }}"></td>
                     <td class="nowrap-td"><input type="text" name="remarks" value="{{ $vehicles->remarks }}"></td>
                     <td class="nowrap-td">{{ $conversions }}</td>
                     <td class="nowrap-td">
    <input type="text" id="variant_name" name="variants_name" list="laList" value="{{ $varaints_name }}">
    <datalist id="laList">
        @foreach ($varaint as $varaints)
            <option value="{{ $varaints->name }}">{{ $varaints->name }}</option>
        @endforeach
    </datalist>
</td>
                        <td class="nowrap-td" id="varaints_detail">{{ $varaints_detail }}</td>
                        <td class="nowrap-td" id="brand_name">{{ $brand_name }}</td>
                        <td class="nowrap-td" id="model_line">{{ $model_line }}</td>
                        <td class="nowrap-td">{{ $vehicles->vin }}</td>
                        <td class="nowrap-td"><input type="text" name="vin" value="{{ $vehicles->vin }}"></td>
                        <td class="nowrap-td"><input type="text" name="engine" value="{{ $vehicles->engine }}"></td>
                        <td class="nowrap-td" id="my">{{ $varaints_my }}</td>
                        <td class="nowrap-td" id="steering">{{ $varaints_steering }}</td>
                        <td class="nowrap-td" id="seat">{{ $varaints_seat }}</td>
                        <td class="nowrap-td" id="fuel">{{ $varaints_fuel_type }}</td>
                        <td class="nowrap-td" id="gearbox">{{ $varaints_gearbox }}</td>
                        <td class="nowrap-td"><input type="text" name="ex_colour" value="{{ $vehicles->ex_colour }}"></td>
                        <td class="nowrap-td"><input type="text" name="int_colour" value="{{ $vehicles->int_colour }}"></td>
                        <td class="nowrap-td" id="upholestry">{{ $varaints_upholestry }}</td>
                        <td class="nowrap-td"><input type="text" name="ppmmyyy" value="{{ $vehicles->ppmmyyy }}"></td>
                        <td class="nowrap-td">{{ $vehicles->vin }}</td>
                        <td class="nowrap-td"><input type="text" name="price" value="{{ $vehicles->price }}"></td>
                        <td class="nowrap-td"><input type="text" name="territory" value="{{ $vehicles->territory }}"></td>
                        <td class="nowrap-td"><input type="text" name="import_type" value="{{ $vehicles->import_type }}"></td>
                        <td class="nowrap-td"><input type="text" name="owership" value="{{ $vehicles->owership }}"></td>
                        <td class="nowrap-td"><input type="text" name="document_with" value="{{ $vehicles->document_with }}"></td>
                        <td class="nowrap-td">
                        <select name="documzinout">
                        <option value="">-</option>
                        <option value="yes" {{ $vehicles->documzinout == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ $vehicles->documzinout == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        </td>
                        <th class="nowrap-td">{{ $bl_number }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <script>
  $(document).ready(function() {
    $('.select2').select2();
    var dataTable = $('#dtBasicSupplierInventory').DataTable({
      ordering: false,
      initComplete: function() {
        this.api().columns().every(function(d) {
          var column = this;
          var theadname = $("#dtBasicSupplierInventory th").eq([d]).text();
          if (d === 12) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          $('<span class="caret"></span>').appendTo($(column.header()).find('select'));

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
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
<script>
  function handleInputChange(input) {
    var newValue = input.value;
    var oldValue = input.getAttribute("data-original-value") || "";
    if (newValue !== oldValue) {
      input.setAttribute("data-original-value", newValue);
      saveChangesToDatabase(input);
    }
  }

  function saveChangesToDatabase(input) {
    var row = input.closest("tr");
    var vehiclesId = row.getAttribute("data-id");
    var columnName = input.name;
    var value = input.value;
    if (columnName === "variants_name") {
      var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      $.ajax({
        url: '{{ route('vehicles.fatchvariantdetails') }}',
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        data: {
          value: value
        },
        success: function(response) {
          console.log(response);
  // Check if properties are null and display "null" if necessary
  var varaintsDetail = response.varaints_detail !== null ? response.varaints_detail : "null";
  var brandName = response.brand_name !== null ? response.brand_name : "null";
  var modelLine = response.model_line !== null ? response.model_line : "null";
  var my = response.my !== null ? response.my : "null";
  var steering = response.steering !== null ? response.steering : "null";
  var seat = response.seat !== null ? response.seat : "null";
  var fuel = response.fuel !== null ? response.fuel : "null";
  var gearbox = response.gearbox !== null ? response.gearbox : "null";
  var upholestry = response.upholestry !== null ? response.upholestry : "null";
  
  // Update the UI with the fetched values
  $('#varaints_detail').text(varaintsDetail);
  $('#brand_name').text(brandName);
  $('#model_line').text(modelLine);
  $('#my').text(my);
  $('#steering').text(steering);
  $('#seat').text(seat);
  $('#fuel').text(fuel);
  $('#gearbox').text(gearbox);
  $('#upholestry').text(upholestry);
},
        error: function(xhr, status, error) {
          console.log(error); // Handle the error gracefully
        }
      });
    }
    axios
      .post("{{ route('vehicles.updatevehiclesdata') }}", {
        vehicles_id: vehiclesId,
        column: columnName,
        value: value
      })
      .then(function(response) {
        console.log(response.data);
      })
      .catch(function(error) {
        console.error(error);
      });
  }
  var inputFields = document.querySelectorAll('input[name]');
  inputFields.forEach(function(input) {
    input.setAttribute("data-original-value", input.value);
    input.addEventListener("change", function() {
      handleInputChange(this);
    });
  });
  var selectElements = document.querySelectorAll('select[name]');
  selectElements.forEach(function(select) {
    select.setAttribute("data-original-value", select.value);
    select.addEventListener("change", function() {
      handleInputChange(this);
    });
  });
</script>
        @endcan
    </div>
@endsection
