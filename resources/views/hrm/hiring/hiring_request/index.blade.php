@extends('layouts.main')
<style>
	.error {
	    color: #FF0000;
	}
	input:focus {
	    border-color: #495057!important;
	}
	select:focus {
	    border-color: #495057!important;
	}
	.paragraph-class {
	    color: red; font-size:11px;
	}
	.overlay {
	    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(128,128,128,0.5); display: none; 
	}
	.drop-class {
	    padding-top:10px;
	}
	.widthinput {
	    height:32px!important;
	}
</style>
@section('content')
<div class="card-header">
	<h4 class="card-title">Employee Hiring Request Form</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
	<form id="createWarrantyForm" name="createWarrantyForm" method="POST" enctype="multipart/form-data" action="">
		@csrf
		<div class="row">
			<p><span style="float:right;" class="error">* Required Field</span></p>
			<div class="card">
				<div class="card-header">
					<h5 class="card-title">Department Information</h5>
				</div>
				<div class="card-body">
					<div class="row">
						
				</div>
			</div>
			<div class="row">
				
				
				
				
				
				
				<div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Type Of Role') }}</label>
                        <fieldset>
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="yes" id="yes" checked />
                                    <label for="yes">New Position</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="no" id="no" />
                                    <label for="no">Replacement</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
				</div>
				<div class="col-xxl-2 col-lg-3 col-md-4">
					<span class="error">* </span>
					<label for="supplier" class="col-form-label text-md-end">Choose Experience Level</label>
					<select name="supplier_id" id="supplier_id" class="form-control widthinput"  multiple="true" autofocus onchange="" >
						@foreach($replacementForEmployees as $replacementForEmployee)
						<option value="{{$replacementForEmployee->id}}">{{$replacementForEmployee->name}}</option>
						@endforeach
					</select>
					<p id="SupplierError" class="invalid-feedback "></p>
				</div>
		</div>
		<div class="col-md-12">
			<button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
		</div>
	</form>
</div>
<input type="hidden" id="indexValue" value="">
<div class="overlay"></div>
<script type="text/javascript">
	$(document).ready(function ()
    {
		$('#department_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Department Name",
        });
		$('#location_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Department Location",
        });
		$('#requested_by').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Requested By",
        });
		$('#requested_job_title').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Requested Job Title",
        });
		$('#reporting_to').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Reporting To",
        });
		
	});
</script>
@endsection