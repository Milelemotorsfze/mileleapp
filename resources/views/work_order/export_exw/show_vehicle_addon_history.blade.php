@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
	.btn-style {
		font-size:0.7rem!important;
		line-height: 0.1!important;
	}
    th {
		font-size:12px!important;
	}
	td {
		font-size:12px!important;
	}
	.form-label {
	margin-top: 0.5rem;
	}
	.iti {
	width: 100%;
	}
	.texttransform {
	text-transform: capitalize;
	}
	.light {
	background-color:#e6e6e6!important;
	font-weight: 700!important;
	}
	.dark {
	background-color:#d9d9d9!important;
	font-weight: 700!important;
	}
	.paragraph-class {
	color: red;
	font-size:11px;
	}
	.other-error {
	color: red;
	}
	.table-edits input, .table-edits select {
	height:38px!important;
	}
</style>
@section('content')
<div class="card-header">
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','export-cnf-wo-details','local-sale-wo-details','create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo','create-lto-wo']);
	@endphp
	@if ($hasPermission)
	<h4 class="card-title">
    @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order Vehicle Addon Data History
	</h4>
	@endif
    <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
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
</div>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','export-cnf-wo-details','local-sale-wo-details','create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo','create-lto-wo']);
@endphp
@if ($hasPermission)
<div class="tab-pane fade show" id="telephonic_interview">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
                <thead style="background-color: #e6f1ff">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($datas) > 0)
                    @foreach($datas as $dataHistory)
                        <tr>
                            <td>{{ $dataHistory->changed_at->format('d M Y, H:i:s') }}</td>
                            <td>{{ $dataHistory->user->name }}</td> 
                            <td>{{ $dataHistory->type }}</td>
                            <td>{{ $dataHistory->field_name }}</td>                  
                            <td>{{ $dataHistory->old_value }}</td>
                            <td>{{ $dataHistory->new_value }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No data history available.</td>
                    </tr>
                @endif
                </tbody>
				</table>
			</div>
		</div>
	</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script type="text/javascript">
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
</script>

@endpush