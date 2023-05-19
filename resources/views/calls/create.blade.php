@extends('layouts.main')
<style>
.select2-container {
  width: 100% !important;
}
.form-label[for="basicpill-firstname-input"] {
  margin-top: 12px;
}
.btn.btn-success.btncenter {
  background-color: #28a745;
  color: #fff;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.btn.btn-success.btncenter:hover {
  background-color: #0000ff;
  font-size: 17px;
  border-radius: 10px;
}
.error 
    {
        color: #FF0000;
    }
    .iti 
    { 
        width: 100%; 
    }
    label {
  display: inline-block;
  margin-right: 10px;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    -moz-appearance: none;
    appearance: none; 
    margin: 0; 
}
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@can('Calls-modified')
<div class="card-header">
        <h4 class="card-title">New Calls & Messages</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('calls.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="col-lg-12">
    <div id="flashMessage"></div>
</div>
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
        {!! Form::open(array('route' => 'calls.store','method'=>'POST')) !!}
            <div class="row">
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Phone : </label>
                        <input type="number" id = "phone" name="phone" class="form-control" placeholder = "Phone Number">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Email : </label>
                        {!! Form::email('email', null, array('id' => 'email', 'placeholder' => 'Email','class' => 'form-control')) !!}
                        <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Source : </label>
                        <select name="source" id="source" class="form-control mb-1" multiple="true">
                                @foreach ($LeadSource as $LeadSource)
                                    <option value="{{ $LeadSource->id }}">{{ $LeadSource->source_name }}</option>
                                @endforeach
                                </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Preferred Language : </label>
                        {{ Form::select('language', [
                        'English' => 'English',
                        'Arabic' => 'Arabic',
                        'Russian' => 'Russian',
                        'Urdu' => 'Urdu',
                        'Hindi' => 'Hindi',
						'Kannada' => 'Kannada',
                        'French' => 'French',
                        'Malayalam' => 'Malayalam',
                        'Tamil' => 'Tamil',
                        'spanish' => 'Spanish',
                        'portuguese' => 'Portuguese',
                        'shona' => 'Shona',
                        ], null, ['class' => 'form-control', 'id' => 'language', 'multiple' => 'true']) }}
                    </div>
                    <div class="col-xs-4 col-sm-12 col-md-4">
                        <label for="basicpill-firstname-input" class="form-label">Destination : </label>
                        <select class="form-control" name="location" id="country" multiple="true">
                        <option value="">Select Destination</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                                            </select>     
                        </div>
                        <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Type : </label>
                        {{ Form::select('type', [
                        'Export' => 'Export',
                        'Local' => 'Local',
						'Other' => 'Other',
                        ], null, ['class' => 'form-control', 'id' => 'type', 'multiple' => 'true']) }}
                    </div>
                    </div>
                    </br>
                    <div class="row">
                    <div class="col-lg-4 col-md-6">
    <label for="sales-options" class="form-label">Sales Persons Options:</label>
    <div>
        <label>
            <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign" checked> System Auto Assign
        </label>
        <label>
            <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign"> Manual Assign
        </label>
    </div>
</div>
<div class="col-lg-4 col-md-6" id="manual-sales-person-list" style="display: none;">
    <label for="manual-sales-person" class="form-label">Sales Person:</label>
    <select name="sales_person" id="sales_persons" class="form-control mb-1" multiple="true">
                                @foreach ($sales_persons as $sales_persons)
                                @php
                     $sales_personsss = DB::table('users')->where('id', $sales_persons->model_id)->first();
                     $sales_persons_name = $sales_personsss->name;
                     @endphp  
                                    <option value="{{ $sales_persons->model_id }}">{{ $sales_persons_name }}</option>
                                @endforeach
                            </select>
</div>
                    </div>
                    <div class="maindd">
    <div id="row-container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Brand & Models: </label>
                <select name="model_line_id[]" id="brand" class="form-control mb-1" multiple="true">
                    <option value="">Select Brand & Model</option>
                    @foreach ($modelLineMasters as $modelLineMaster)
                    @php
                    $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                    $brand_name = $brand->brand_name;
                    @endphp 
                    <option value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
        <div class="btn btn-primary add-row-btn">
            <i class="fas fa-plus"></i> Add More
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
                        {!! Form::text('custom_brand_model', null, array('placeholder' => 'Custom Brand & Model','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        <textarea name="remarks" id="editor"></textarea>
                    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
			        </div>  
		{!! Form::close() !!}
		</br>
    </div>
    @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
         $(document).ready(function ()
        {
$("#brand").select2({
            allowClear: true,
            placeholder: 'Select Brand & Model',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#brand").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
$("#country").select2({
            allowClear: true,
            placeholder: 'Select Destination',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#country").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
$("#language").select2({
            allowClear: true,
            placeholder: 'Select language',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#language").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
$("#source").select2({
            allowClear: true,
            placeholder: 'Select source',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#source").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
$("#type").select2({
            allowClear: true,
            placeholder: 'Select Type',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#type").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
$("#sales_persons").select2({
            allowClear: true,
            placeholder: 'Select Sales Persons',
            closeOnSelect: true,
            dropdownAutoWidth: true,
            dropdownParent: $("#sales_persons").parent(),
            selectOnClose: true,
            tags: false, // Disable custom tags
            maximumSelectionLength: 1,
        });
        });
    // Add event listeners to the radio buttons to show/hide the manual sales person list and set the selected option value
    const autoAssignOption = document.getElementById('auto-assign-option');
    const manualAssignOption = document.getElementById('manual-assign-option');
    const manualSalesPersonList = document.getElementById('manual-sales-person-list');
    const salesOptionValueField = document.getElementById('sales-option-value');

    autoAssignOption.addEventListener('change', () => {
        manualSalesPersonList.style.display = 'none';
        salesOptionValueField.value = autoAssignOption.value;
    });

    manualAssignOption.addEventListener('change', () => {
        manualSalesPersonList.style.display = 'block';
        salesOptionValueField.value = manualAssignOption.value;
    });
    $(document).ready(function() {
    // Initialize select2 on the initial dropdown
    $('#brand').select2();

    var max_fields = 10; //maximum input fields allowed
    var wrapper = $("#row-container"); //input fields wrapper
    var add_button = $(".add-row-btn"); //add button class

    var x = 1; //initlal text box count
    $(add_button).click(function(e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment
            // Add new row
            $(wrapper).append('<br><div class="row"><div class="col-lg-4 col-md-6"><select name="model_line_id[]" class="form-control mb-1 new-select" multiple="true"><option value="">Select Brand & Model</option>@foreach($modelLineMasters as $modelLineMaster)@php $brand = DB::table("brands")->where("id", $modelLineMaster->brand_id)->first(); $brand_name =$brand->brand_name; @endphp <option value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>@endforeach</select></div><div class="col-lg-4 col-md-6"><a href="#" class="remove-row-btn btn btn-danger"><i class="fas fa-minus"></i> Remove</a></div></div>');
            // Initialize select2 on the new dropdown
            $('.new-select').last().select2();
        }
    });
    $(wrapper).on("click", ".remove-row-btn", function(e) { //user click on remove text
        e.preventDefault();
        $(this).parent('div').parent('div').remove();
        x--;
    })
});
    $(document).ready(function() {
        $('#phone, #email').on('input', function() {
            var phone = $('#phone').val();
            var email = $('#email').val();
            
            $.ajax({
                url: "{{ route('checkExistence') }}",
                method: 'POST',
                data: {
                    phone: phone,
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#phoneCount').text('Phone count: ' + response.phoneCount);
                    $('#emailCount').text('Email count: ' + response.emailCount);
                    
                    // Display flash message
                    if (response.phoneCount > 0 || response.emailCount > 0) {
                        var customerNames = response.customerNames.join(', '); // Join names with commas
                        var message = 'Customer Names: ' + customerNames + '<br>';
                        message += 'Phone Count: ' + response.phoneCount + '<br>';
                        message += 'Email Count: ' + response.emailCount;
                        
                        // Add button to send details to another page
                        var buttonHtml = '<a href="{{ route('repeatedcustomers') }}?phone=' + phone + '&email=' + email + '" class="btn btn-primary">See Details</a>';
                        message += '<br>' + buttonHtml;
                        
                        $('#flashMessage').html('<div class="alert alert-info">' + message + '</div>');
                    } else {
                        $('#flashMessage').html('');
                    }
                }
            });
        });
    });
</script>
@endpush