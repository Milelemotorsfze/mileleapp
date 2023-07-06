@extends('layouts.table')
<style>
.editing {
    background-color: white !important;
    border: 1px solid black  !important;
}

    </style>
@section('content')
    <div class="card-header">
        @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $previousId) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Purchase Order Number : {{$purchasingOrder->po_number}}</b> 
@if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $nextId) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    @php
    $exColours = \App\Models\ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
    $intColours = \App\Models\ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
@endphp
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" >
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">PO Date</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{$purchasingOrder->po_date}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Vendor Name</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{$vendorsname}}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicle Details</h4>
                    <a href="#" class="btn btn-sm btn-primary float-end edit-btn">Edit</a>
                    <a href="#" class="btn btn-sm btn-success float-end update-btn" style="display: none;">Updated</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                            <tr >
                                <th id="serno" style="vertical-align: middle;">S.No</th>
                                <th>Variants</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variants Detail</th>
                                <th>Estimated Arrival</th>
                                <th>Territory</th>
                                <th>Exterior Color</th>
                                <th>Interior Color</th>
                                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
                    @endphp
                    @if ($hasPermission)
                                <th>Payment Status</th>
                                @endif
                                <th>VIN</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach($vehicles as $vehicles)
                                <tr>
                                @php
                            $variant = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                            $name = $variant->name;
                            $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;
                            $ex_colours = $exColour ? $exColour->name : null;
                            $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;
                            $int_colours = $intColour ? $intColour->name : null;
                            $detail = $variant->detail;
                            $brands_id = $variant->brands_id;
                            $master_model_lines_id = $variant->master_model_lines_id;
                            $brand = DB::table('brands')->where('id', $brands_id)->first();
                            $brand_names = $brand->brand_name;
                            $master_model_lines_ids = DB::table('master_model_lines')->where('id', $master_model_lines_id)->first();
                            $model_line = $master_model_lines_ids->model_line;
                            @endphp 
                            <td>{{ ++$i }}</td>
                            <td>{{ $name }}</td>
                            <td>{{ $brand_names }}</td>
                            <td>{{ $model_line }}</td>
                            <td>{{ $detail }}</td>
                            <td class="editable-field" contenteditable="false">{{ $vehicles->estimation_date }}</td>
                            <td class="editable-field" contenteditable="false">{{ $vehicles->territory }}</td>
                            <td class="editable-field exterior-color" contenteditable="false">
                            <select name="oldex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                            <option value="">Exterior Color</option>
                            @foreach ($exColours as $id => $exColour)
                                @if ($id == $vehicles->ex_colour)
                                    <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                @else
                                    <option value="{{ $id }}">{{ $exColour }}</option>
                                @endif
                            @endforeach
                             </select>
                            </td>
                            <td class="editable-field interior-color" contenteditable="false"><select name="oldint_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                <option value="">Interior Color</option>
                                @foreach ($intColours as $id => $intColour)
                                    @if ($id == $vehicles->int_colour)
                                        <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                    @else
                                        <option value="{{ $id }}">{{ $intColour }}</option>
                                    @endif
                                @endforeach
                            </select>
                            </td>
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
                            @endphp
                            @if ($hasPermission)
                                <td class="editable-field" contenteditable="false">{{ $vehicles->payment_status }}</td>
                            @endif
                            <td class="editable-field" contenteditable="false">{{ $vehicles->vin }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Log Details</h4>
                </div>
                <div class="card-body">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
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
           
        </tbody>
    </table>
</div>
</div>
</div>
    </div>
    <script>
  $(document).ready(function() {
    $('.select2').select2();
  var dataTable = $('#dtBasicExample2').DataTable({
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
// Get all editable fields
const editableFields = document.querySelectorAll('.editable-field');

// Get the Edit button and Update Success button
const editBtn = document.querySelector('.edit-btn');
const updateBtn = document.querySelector('.update-btn');

// Add event listener to the Edit button
editBtn.addEventListener('click', () => {
    // Toggle the Edit and Update Success buttons
    editBtn.style.display = 'none';
    updateBtn.style.display = 'block';

    // Enable editing for all editable fields and change their color
    editableFields.forEach(field => {
        field.contentEditable = true;
        field.classList.add('editing');
         // Remove the "disabled" attribute from the select elements
        const selectElement = field.querySelector('select');
        if (selectElement) {
            selectElement.removeAttribute('disabled');
        }
        // Check if the field contains a date value
        const fieldValue = field.innerText.trim();
        if (isValidDate(fieldValue)) {
            // Replace the non-editable field with an editable input field
            const inputField = document.createElement('input');
            inputField.type = 'date';
            inputField.name = 'oldestimated_arrival[]';
            inputField.value = fieldValue;
            inputField.classList.add('form-control');
            
            // Replace the field with the input field
            field.innerHTML = '';
            field.appendChild(inputField);
        }
    });
});

// Add event listener to the Update Success button
updateBtn.addEventListener('click', () => {
    // Toggle the Update Success and Edit buttons
    updateBtn.style.display = 'none';
    editBtn.style.display = 'block';

    // Disable editing for all editable fields and change their color back to default
    editableFields.forEach(field => {
        field.contentEditable = false;
        field.classList.remove('editing');
		// Add the "disabled" attribute to the select elements
        const selectElement = field.querySelector('select');
        if (selectElement) {
            selectElement.setAttribute('disabled', 'disabled');
        }
        
        // Remove the input field and restore the original non-editable field content
        const inputField = field.querySelector('input[type="date"]');
        if (inputField) {
            const fieldValue = inputField.value;
            field.innerHTML = fieldValue;
        }
    });
    
    // Perform the necessary actions to update the values (e.g., submit the form via AJAX or redirect to a controller)
    // You can access the updated field values using the "innerText" property of each editable field
});

// Helper function to validate a date string
function isValidDate(dateString) {
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
    return dateRegex.test(dateString);
}
</script>
@endsection