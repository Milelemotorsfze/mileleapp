@extends('layouts.main')
@include('layouts.formstyle')
@section('content')

@php
$isShow = Request::is('*/show')
@endphp

<div class="card-header">
    <h4 class="card-title">Salary Certificate Request</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <form id="updateEmployeeSalaryCertificateRequestForm" name="updateEmployeeSalaryCertificateRequestForm" action="{{ route('employeeRelation.salaryCertificate.update', $certificate->id) }}" method="POST">
        @csrf
        @method('POST')
        <div class="row {{$isShow == 1 ? 'd-none': ''}}">
            <div class="col-xxl-2 col-lg-6 col-md-6">
                <span class="error">* </span>
                <label for="creation_date" class="col-form-label text-md-end"> <b>{{ __('Choose Date') }}</b></label>
                <input type="date" name="creation_date" id="creation_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2" value="{{$certificate->creation_date ?? ''}}"></input>
                @if ($errors->has('creation_date'))
                <span class="text-danger">{{ $errors->first('creation_date') }}</span>
                @endif
            </div>
            <div class="col-xxl-10 col-lg-6 col-md-6">
                <p><span style="float:right;" class="error">* Required Field</span></p>
            </div>
        </div>
        <br />
        <div class="card {{$isShow == 1 ? 'd-none': ''}}">
            <div class="card-header">
                <h4 class="card-title">Salary Certificate Data</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6" id="passport-number-container">
                        <label for="passport_number" class="col-form-label"><span class="error">*</span> <b> Passport No.</b> </label>
                        <input type="text" name="passport_number" class="form-control" id="passport_number-input" placeholder="Enter Passport Number" value="{{$certificate->passport_number ?? ''}}" required />
                        @if ($errors->has('passport_number'))
                        <span class="text-danger">{{ $errors->first('passport_number') }}</span>
                        @endif
                    </div>

                    <div class="col-lg-4 col-md-6" id="issued-by-container">
                        <label for="issued_by" class="col-form-label"><span class="error">*</span> <b> Issued By </b></label>
                        <input type="text" name="issued_by" class="form-control" id="issued_by-input" placeholder="Country Name" value="{{$certificate->issued_by ?? ''}}" required />
                        @if ($errors->has('issued_by'))
                        <span class="text-danger">{{ $errors->first('issued_by') }}</span>
                        @endif
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
                        <div class="dropdown-option-div">
                            <span class="error">* </span>
                            <label for="company_branch" class="col-form-label text-md-end"><b>{{ __(' Company Branch  ') }}</b></label>
                            <select name="company_branch" class="form-control" id="company_branch" required>
                                <option value="" disabled selected>Select Company</option>
                                <option value="milele_motors_fze" {{ $certificate->company_branch == 'milele_motors_fze' ? 'selected' : '' }}>Milele Motors FZE</option>
                                <option value="milele_fze" {{ $certificate->company_branch == 'milele_fze' ? 'selected' : '' }}>Milele FZE</option>
                                <option value="miele_auto_fze" {{ $certificate->company_branch == 'miele_auto_fze' ? 'selected' : '' }}>Milele Auto FZE</option>
                                <option value="milele_cars_trading_llc" {{ $certificate->company_branch == 'milele_cars_trading_llc' ? 'selected' : '' }}>Milele Cars Trading LLC</option>
                                <option value="milele_car_rental_llc" {{ $certificate->company_branch == 'milele_car_rental_llc' ? 'selected' : '' }}>Milele Car Rental LLC</option>
                                <option value="trans_car_fze" {{ $certificate->company_branch == 'trans_car_fze' ? 'selected' : '' }}>Trans Car FZE</option>
                            </select>
                            @if ($errors->has('company_branch'))
                            <span class="text-danger">{{ $errors->first('company_branch') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
                        <div class="dropdown-option-div">
                            <span class="error">* </span>
                            <label for="requested_job_title" class="col-form-label text-md-end"><b>{{ __('  Job Title ') }}</b></label>
                            <select name="requested_job_title" id="requested_job_title" multiple="true" class="form-control widthinput" onchange="" autofocus>
                                @foreach($masterJobPositions as $masterJobPosition)
                                <option value="{{ $masterJobPosition->id }}" {{ $certificate->requested_job_title == $masterJobPosition->id ? 'selected' : '' }}>
                                    {{ $masterJobPosition->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="salary_in_aed" class="col-form-label text-md-end"><b>{{ __(' Salary ') }}</b></label>
                        <div class="input-group">
                            <input type="number" name="salary_in_aed" id="salary_in_aed"
                                class="form-control widthinput" placeholder="Enter Salary"
                                aria-label="measurement" aria-describedby="basic-addon2" value="{{ $certificate->salary_in_aed > 0 ? $certificate->salary_in_aed : '' }}" />
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                            </div>
                        </div>
                        <span id="salary-error" class="text-danger d-none">Salary must be greater than 0</span>
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="joining_date" class="col-form-label text-md-end"><b>{{ __(' Joining Date ') }}</b></label>
                        <input type="date" name="joining_date" id="joining_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2" value="{{$certificate->joining_date ?? ''}}" />
                        @if ($errors->has('joining_date'))
                        <span class="text-danger">{{ $errors->first('joining_date') }}</span>
                        @endif
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
                        <div class="dropdown-option-div">
                            <span class="error">* </span>
                            <label for="status" class="col-form-label text-md-end"><b> {{ __('Request Status') }}</b></label>
                            <select name="status" class="form-control" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="approved" {{ $certificate->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $certificate->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-xxl-8 col-lg-6 col-md-6 select-button-main-div">
                        <div class="col-lg-12  job-description-lable-name-1">
                            <label for="comments" class="col-form-label heading-name"><b> Additional Comments (Optional)</b></label>
                        </div>
                        <div class="col-lg-12">
                            <textarea cols="25" rows="1" class="form-control" name="comments" placeholder="Comments ">{{$certificate->comments ?? ''}}</textarea>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="card">

            @php
            $branchMapping = [
            'milele_fze' => 'Milele FZE',
            'milele_motors_fze' => 'Milele Motors FZE',
            'miele_auto_fze' => 'Milele Auto FZE',
            'milele_cars_trading_llc' => 'Milele Cars Trading LLC',
            'milele_car_rental_llc' => 'Milele Car Rental LLC',
            'trans_car_fze' => 'Trans Car FZE',
            ];

            $branchName = $branchMapping[$certificate->company_branch] ?? '_________';
            @endphp

            <div>
                <div class="card-header">
                    <h4 class="card-title">Salary Certificate Preview</h4>
                </div>
                <div class="card-body">

                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <!-- <div class="salary-certificate-company-branch-name text-center">
                            <h3><span id="heading-company-branch">{{ $branchName }}</span></h3>
                        </div> -->
                        <div><strong>Date: </strong>
                            <span id="preview-request-date">
                                {{ isset($certificate->creation_date) ? \Carbon\Carbon::parse($certificate->creation_date)->format('F d, Y') : '_________' }}
                            </span>
                        </div>
                        </br>
                        <p>To,</p>
                        <div>
                            <span>{{$certificate->bank_name ?? '_________'}} </br> {{$certificate->branch_name ?? '_________'}} </br> {{$certificate->country_name ?? '_________'}}</span>
                        </div>
                        </br>
                        <div class="text-center">
                            <h3>Salary Certificate</h3>
                        </div>


                        @php
                        function numberToWords($num) {
                        $a = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
                        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
                        $b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
                        $g = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];

                        if ($num == 0) return 'zero';

                        $word = '';
                        $group = 0;

                        while ($num > 0) {
                        $groupOfThree = $num % 1000;
                        $num = floor($num / 1000);

                        if ($groupOfThree > 0) {
                        $groupWord = '';

                        if ($groupOfThree > 99) {
                        $groupWord .= $a[floor($groupOfThree / 100)] . ' hundred ';
                        $groupOfThree %= 100;
                        }

                        if ($groupOfThree > 19) {
                        $groupWord .= $b[floor($groupOfThree / 10)] . ' ';
                        $groupOfThree %= 10;
                        }

                        $groupWord .= $a[$groupOfThree] . ' ';
                        $word = $groupWord . $g[$group] . ' ' . $word;
                        }

                        $group++;
                        }

                        return trim($word);
                        }
                        @endphp


                        <div>
                            <p>This is to certify that <span id="preview-employee-name">{{ $certificate->requestedFor ? $certificate->requestedFor->name : 'N/A' }}</span>,
                                holding Passport Number <span id="preview-passport-number">{{ $certificate->passport_number ?? '_________' }}</span>,
                                issued by the <span id="preview-issued-by">{{ $certificate->issued_by ?? '_________' }}</span>,
                                is a permanent employee of our esteemed <span id="preview-company-branch">{{ $branchName }}</span>.
                                He is serving as a “<span id="preview-job-title">{{ $certificate->jobTitle ? $certificate->jobTitle->name : '_________' }}</span>” since
                                <span id="preview-joining-date">{{ $certificate->joining_date ? \Carbon\Carbon::parse($certificate->joining_date)->format('F d, Y') : '_________' }}</span>.
                                He is currently withdrawing a monthly salary of AED
                                <span id="preview-salary">{{ $certificate->salary_in_aed > 0 ? number_format($certificate->salary_in_aed, 2) : '_________' }}</span>
                                (<span id="salary-in-words">{{ $certificate->salary_in_aed > 0 ? numberToWords($certificate->salary_in_aed) : '_________' }}</span> UAE dirhams only).
                            </p>

                            <p>This certificate is issued upon the request of the employee, and <span id="preview-company-branch-2">{{ $branchName }}</span> does not bear any legal or financial responsibility for any issues that may arise with this certificate.</p>
                            </br>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-12 col-lg-12 col-md-12 {{$isShow == 1 ? 'd-none': ''}}">
            <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
        </div>
    </form>
    <br />
    <br />


    <div class="card">
        <div class="card-header">
            <strong>Submitted Details</strong>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label for="employee_name">Employee Name:</label>
                <p>{{ $certificate->creator ? $certificate->requestedFor->name : 'N/A'  }}</p>
            </div>

            <div class="form-group">
                <label for="submitting_to">Bank Name:</label>
                <p>{{ $certificate->bank_name }}</p>
            </div>

            <div class="form-group">
                <label for="purpose_of_request">Purpose of Request:</label>
                <p>{{ $certificate->purpose_of_request }}</p>
            </div>

            <div class="form-group">
                <label for="status">Current Status:</label>
                <p>{{ ucfirst($certificate->status) }}</p>
            </div>

            @if ($certificate->status != 'pending')
            <div class="form-group">
                <label for="comments">Reviewer Comments:</label>
                <p>{{ $certificate->comments ?? 'No comments yet.' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>


@endsection


@push('scripts')

<script type="text/javascript">
    function numberToWords(num) {
        const a = [
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];
        const b = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];
        const g = [
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'
        ];

        if (typeof num === 'string') num = parseInt(num);
        if (isNaN(num) || num === 0) return 'zero';

        let word = '';
        let group = 0;

        while (num > 0) {
            let groupOfThree = num % 1000;
            num = Math.floor(num / 1000);

            if (groupOfThree > 0) {
                let groupWord = '';

                if (groupOfThree > 99) {
                    groupWord += a[Math.floor(groupOfThree / 100)] + ' hundred ';
                    groupOfThree %= 100;
                }

                if (groupOfThree > 19) {
                    groupWord += b[Math.floor(groupOfThree / 10)] + ' ';
                    groupOfThree %= 10;
                }

                groupWord += a[groupOfThree] + ' ';
                word = groupWord + g[group] + ' ' + word;
            }

            group++;
        }

        return word.trim();
    }


    $(document).ready(function() {

        var today = new Date();
        var formattedDate = today.toISOString().split('T')[0];
        var readableDate = today.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('creation_date').setAttribute('min', formattedDate);

        var existingCreationDate = "{{ $certificate->creation_date ?? '' }}";

        if (existingCreationDate && existingCreationDate !== '0000-00-00') {
            var existingDate = new Date(existingCreationDate);
            var formattedExistingDate = existingDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('creation_date').value = existingCreationDate;
            $('#preview-request-date').text(formattedExistingDate);
        } else {
            document.getElementById('creation_date').valueAsDate = today;
            $('#preview-request-date').text(readableDate);
        }

        $('#creation_date').on('input', function() {
            var requestDate = new Date($(this).val());
            var formattedRequestDate = requestDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            $('#preview-request-date').text(formattedRequestDate || '--');
        });



        $('#requested_job_title').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Job Title",
        });
        $('#salary_in_aed').on('input', function() {
            var salary = parseFloat($(this).val());
            var salaryInWords = salary > 0 ? numberToWords(salary) : '_________';
            if (salary <= 0 || isNaN(salary)) {
                $('#salary-error').removeClass('d-none');
                $('#submit').prop('disabled', true);
            } else {
                $('#salary-error').addClass('d-none');
                $('#submit').prop('disabled', false);
            }
            $('#salary-in-words').text(salaryInWords);
            $('#preview-salary').text(salary > 0 ? salary : '_________');
        });

        // Real-time preview updates
        $('#passport_number-input').on('input', function() {
            $('#preview-passport-number').text($(this).val() || '_________');
        });
        $('#issued_by-input').on('input', function() {
            $('#preview-issued-by').text($(this).val() || '_________');
        });

        $('#company_branch').on('change', function() {
            var selectedBranch = $(this).find("option:selected").text() || '_________';
            // $('#heading-company-branch').text(selectedBranch);
            $('#preview-company-branch').text(selectedBranch);
            $('#preview-company-branch-2').text(selectedBranch);
        });

        $('#requested_job_title').on('change', function() {
            $('#preview-job-title').text($(this).find("option:selected").text() || '_________');
        });

        $('#joining_date').on('input', function() {
            var joiningDate = new Date($(this).val());
            var formattedJoiningDate = joiningDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            $('#preview-joining-date').text(formattedJoiningDate || '_________');
        });

        $('#creation_date').on('input', function() {
            var requestDate = new Date($(this).val());
            var formattedRequestDate = requestDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            $('#preview-request-date').text(formattedRequestDate || '--');
        });
    })

    $('#updateEmployeeSalaryCertificateRequestForm').validate({
        rules: {
            creation_date: {
                required: true,
            },
            passport_number: {
                required: true,
            },
            issued_by: {
                required: true,
            },
            company_branch: {
                required: true,
            },
            requested_job_title: {
                required: true,
            },
            status: {
                required: true,
            },
            salary_in_aed: {
                required: true,
            },
            joining_date: {
                required: true,
            },
        },
    });
</script>
@endpush