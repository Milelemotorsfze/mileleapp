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
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@can('Calls-modified')
<div class="card-header">
        <h4 class="card-title">New Calls & Messages</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('calls.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
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
                        <input type="number" name="phone" class="form-control" value="">
                        
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Email : </label>
                        {!! Form::email('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                        <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Source : </label>
                        <select name="source" id="source" class="form-control mb-1">
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
                        ], null, ['class' => 'form-control', 'id' => 'language']) }}
                    </div>
                    <div class="col-xs-4 col-sm-12 col-md-4">
                        <label for="basicpill-firstname-input" class="form-label">Destination : </label>
                            <select name="location" id="country" class="form-control mb-1">
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
                        ], null, ['class' => 'form-control', 'id' => 'type']) }}
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
    <select name="sales_person" id="sales_persons" class="form-control mb-1">
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
                    <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Brand & Models: </label>
                            <select name="brand_id" id="brand" class="form-control mb-1">
                                <option value="">Select Brand</option>
                                @foreach ($modelLineMasters as $modelLineMasters)
                                @php
                                $brand = DB::table('brands')->where('id', $modelLineMasters->brand_id)->first();
                                $brand_name = $brand->brand_name;
                                @endphp 
                                    <option value="{{ $modelLineMasters->id }}">{{ $brand_name }} / {{ $modelLineMasters->model_line }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="col-lg-8 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
                        {!! Form::text('custom_brand_model', null, array('placeholder' => 'Custom Brand & Model','class' => 'form-control')) !!}
                    </div>
                    </div>
                    <div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
        <div class="btn btn-primary add-row-btn">
            <i class="fas fa-plus"></i> Add More
        </div>
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
$('#model').select2();
$('#brand').select2();
$('#country').select2();
$('#language').select2();
$('#source').select2();
$('#type').select2();
$('#sales_persons').select2();
$('#brand').on('change',function(){
            let brand = $(this).val();
            let url = '{{ route('calls.get-modellines') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    brand: brand
                },
                success:function (data) {
                    $('select[name="model_line_id"]').empty();
                    $('#model').html('<option value=""> Select Model Line </option>');
                    jQuery.each(data, function(key,value){
                        $('select[name="model_line_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        });
</script>
<script>
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
</script>
@endpush