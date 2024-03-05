@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@canany(['edit-division'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-division']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Edit Division Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('division.edit',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('division.edit',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('division.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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

		
		<form id="editDivisionForm" name="editDivisionForm" enctype="multipart/form-data" method="POST" action="{{route('division.update',$data->id)}}">
		@csrf
		@method("PUT")

		<div class="row">
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>			
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Division Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-4 col-md-4" id="other_leave_type">
						<span class="error">* </span>
						<label for="name" class="col-form-label text-md-end">{{ __('Department Name') }}</label>
						<input type="text" name="name" id="name"
								class="form-control widthinput" placeholder="Enter Leave Type If Others"
								 aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->name ?? ''}}">
					</div>
                    <div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
					<div class="dropdown-option-div">
						<span class="error">* </span>
						<label for="division_head_id" class="col-form-label text-md-end">{{ __('Division Head Name') }}</label>
                        <select name="division_head_id" id="division_head_id" multiple="true" class="division_head_id form-control widthinput" onchange="" autofocus>
                            @foreach($divisionHeads as $divisionHead)
                                <option value="{{$divisionHead->id}}" @if($data->division_head_id == $divisionHead->id) selected @endif>{{$divisionHead->name}}</option>
                            @endforeach
                        </select>
					</div>
					</div>	 
					<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
					<div class="dropdown-option-div">
						<span class="error">* </span>
						<label for="approval_handover_to" class="col-form-label text-md-end">{{ __('Division Head Approval Hand Over To') }}</label>
                        <select name="approval_handover_to" id="approval_handover_to" multiple="true" class="approval_handover_to form-control widthinput" onchange="" autofocus>
                            @foreach($divisionHeads as $approvalBy)
                                <option value="{{$approvalBy->id}}" @if($data->approval_handover_to == $approvalBy->id) selected @endif>{{$approvalBy->name}}</option>
                            @endforeach
                        </select>
					</div>
					</div>	           
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
<div class="overlay"></div>
@endif
@endcanany
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	var data = {!! json_encode($divisionHeads) !!};
	var oldData = {!! json_encode($data) !!};
	$(document).ready(function () {
		$('#division_head_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
		$('#approval_handover_to').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
	});	
	jQuery.validator.setDefaults({
        errorClass: "is-invalid",
        errorElement: "p",
        errorPlacement: function ( error, element ) {
            error.addClass( "invalid-feedback font-size-13" );
			if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
                if (!element.val() || element.val().length === 0) {
                    console.log("Error is here with length", element.val().length);
                    error.addClass('select-error');
                    error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
                } else {
                    console.log("No error");
                }
            }
            else {
                error.insertAfter( element );
            }
        }
    });
	$('#editDivisionForm').validate({ // initialize the plugin
        rules: {           
            name: {
                required: true,
            },
			division_head_id: {
                required: true,
            },
			approval_handover_to: {
				required: true,
			},
        },
    });
</script>
@endsection