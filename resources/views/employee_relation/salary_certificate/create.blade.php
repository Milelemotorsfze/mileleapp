@extends('layouts.main')
@include('layouts.formstyle')
@section('content')

<style>
    .hidden {
        display: none;
    }

    .custom-error {
        color: red !important; 
    }

</style>
@section('content')
<div class="card-header">
    <h4 class="card-title">Create Salary Certification Request</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <form id="createEmployeeSalaryCertificateRequestForm" name="createEmployeeSalaryCertificateRequestForm" action="{{ route('employeeRelation.salaryCertificate.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-request-for-employee']);
            @endphp
            <div class="col-lg-3 col-md-6">
                <label for="requested_for" class="form-label heading-name pb-1"><span class="error">* </span><b>{{ __('Requesting For') }}</b></label>
                <select name="requested_for" id="requested_for_id" class="form-control widthinput" multiple="true" onchange="" autofocus
                    @if(!$hasPermission) disabled @endif>

                    @if($hasPermission)
                    @foreach($masterEmployees as $User)
                    <option value="{{ $User->id }}">{{ $User->name ?? ''}}</option>
                    @endforeach
                    @else
                    <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}</option>
                    @endif

                </select>
                @if(!$hasPermission)
                <input type="hidden" name="requested_for" value="{{ Auth::user()->id }}">
                @endif
            </div>

            <div class="col-lg-3 col-md-6">
                <label for="purpose_of_request" class="form-label"><span class="error">* </span><b> Request Category</b> </label>
                <select name="purpose_of_request" class="form-control " id="category-input" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="Bank Related">Bank Related</option>
                    <option value="Others">Other</option>
                </select>
                @if ($errors->has('purpose_of_request'))
                <span class="text-danger">{{ $errors->first('purpose_of_request') }}</span>
                @endif
            </div>

            <div class="col-lg-3 col-md-6 hidden" id="salary_certficate_request_detail_input">
                <label for="salary_certficate_request_detail" class="form-label"><span class="error">* </span><b> Enter Request Details</b> </label>
                <input type="text" name="salary_certficate_request_detail" class="form-control" id="salary_certficate_request_detail-input" placeholder="Enter Details" value="Related to Bank">
                @if ($errors->has('salary_certficate_request_detail'))
                <span class="text-danger">{{ $errors->first('salary_certficate_request_detail') }}</span>
                @endif
            </div>

            <div class="col-lg-9 col-md-6  hidden pt-2" id="submitting-to-container">
                <div class=" row" id="submitting-to-container">
                    <div class="col-lg-4 col-md-4">
                        <label for="bank_name" class="form-label"><span class="error">* </span><b> Bank/Organization Name</b> </label>
                        <input type="text" name="bank_name" class="form-control" id="bank_name-input" placeholder="Enter Name" required>
                        @if ($errors->has('bank_name'))
                        <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                        @endif
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <label for="branch_name" class="form-label"><span class="error">* </span><b> Branch Name (Location)</b> </label>
                        <input type="text" name="branch_name" class="form-control" id="branch_name-input" placeholder="Enter Area Name" required>
                        @if ($errors->has('branch_name'))
                        <span class="text-danger">{{ $errors->first('branch_name') }}</span>
                        @endif
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label for="country_name" class="form-label"><span class="error">* </span><b> Country</b></label>
                        <input type="text" name="country_name" class="form-control" id="country_name-input" placeholder="Enter details" required>
                        @if ($errors->has('country_name'))
                        <span class="text-danger">{{ $errors->first('country_name') }}</span>
                        @endif
                    </div>
                </div>

            </div>

        </div>
        <br>
        <div class="col-lg-12 col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#requested_for_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Requesting For",
        });
    })

    $('#category-input').change(function() {
        var category = $(this).val();
        var salaryCertDetailInput = $('#salary_certficate_request_detail_input');
        var salaryCertDetailFields = $('#salary_certficate_request_detail-input');
        var submittingToContainer = $('#submitting-to-container');

        if (category === 'Others') {
            salaryCertDetailInput.removeClass('hidden');
            salaryCertDetailFields.val('').prop('readonly', false);
            submittingToContainer.removeClass('hidden');
        } else if (category === 'Bank Related') {
            salaryCertDetailInput.removeClass('hidden');
            salaryCertDetailFields.val('Related to Bank').prop('readonly', true);
            submittingToContainer.removeClass('hidden');
        } else {
            salaryCertDetailInput.addClass('hidden');
            submittingToContainer.addClass('hidden');
        }
    });


    $('#createEmployeeSalaryCertificateRequestForm').validate({
        rules: {
            requested_for: {
                required: true,
            },
            purpose_of_request: {
                required: true,
            },
            bank_name: {
                required: true,
            },
            branch_name: {
                required: true,
            },
            country_name: {
                required: true,
            },
            salary_certficate_request_detail: {
                required: function() {
                    return $('#category-input').val() === 'Others';
                }
            },
        },
        errorPlacement: function(error, element) {
            error.addClass('custom-error');
            if (element.attr("name") === "requested_for") {
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        }
    });
</script>
@endpush