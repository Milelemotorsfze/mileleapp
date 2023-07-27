@extends('layouts.main')
@section('content')
    <style>
        iframe {
            min-height: 300px;
            max-height: 500px;
        }
        .iti{
            width: 100%;
        }
        .iti__selected-flag{
            height: 36px;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">Add New Vendor</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

    </div>
    <div class="card-body">
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
        <form id="form-create" action="{{ route('vendors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Primary Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Vendor Type</label>
                                <select class="form-control" name="vendor_type" id="vendor-type" autofocus>
                                    <option value="Individual">Individual</option>
                                    <option value="Company">Company</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Category</label>
                                <select class="form-control" name="category" >
                                    <option value="IT">IT</option>
                                    <option value="vehicle-procurment">Vehicle Procurment</option>
                                    <option value="parts-procurment">Parts Procurment</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Trade / Individual Name </label>
                                <input type="text" class="form-control @error('trade_name_or_individual_name') is-invalid @enderror"
                                       id="trade_name_or_individual_name" name="trade_name_or_individual_name" placeholder="Trade / Individual Name">
                                <span id="poNumberError" class="error" style="display: none;"></span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">comment</label>
                                <textarea cols="25" rows="5" class="form-control" name="comment" placeholder="comment"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Web Address</label>
                                <input type="text" class="form-control"  name="web_address" placeholder="Web Address">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Classification</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">ID Number</label>
                                <input type="text" class="form-control" name="Id_number" placeholder="ID Number"  autofocus>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Primary Subsidiary</label>
                                <input type="text" class="form-control" name="primary_subsidiary" placeholder="Primary Subsidiary" >
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Business Registration</label>
                                <input type="text" class="form-control @error('business_registration') is-invalid @enderror"
                                       name="business_registration" placeholder="Business Registration">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Reference</label>
                                <input type="text" class="form-control"  name="reference" placeholder="Reference">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Passport Number</label>
                                <input type="text" class="form-control" name="passport_number" placeholder="Passport Number">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Nationality</label>
                                <input type="text" class="form-control" name="nationality" placeholder="Nationality">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Trade License Number</label>
                                <input type="text" class="form-control" name="trade_license_number" placeholder="Trade License Number">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Trade Registration Place</label>
                                <input type="text" class="form-control" name="trade_registration_place" placeholder="Trade Registration Place">
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Upload Documents</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="choices-single-default" class="form-label">Passport</label>
                                            <input type="file" class="form-control" id="passport-upload" name="passport_copy_file"
                                                   accept="application/pdf, image/*">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="choices-single-default" class="form-label">Trade License </label>
                                            <input type="file" class="form-control" id="trade-licence-upload" name="trade_license_file"
                                                   placeholder="Upload Trade License" accept="application/pdf, image/*">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label for="choices-single-default" class="form-label">Vat Certificate</label>
                                            <input type="file" class="form-control" id="vat-certificate-upload" name="vat_certificate_file"
                                                   placeholder="Upload Vat Certificate" accept="application/pdf, image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div id="file1-preview">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div id="file2-preview">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div id="file3-preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Contact Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control mygroup @error('email') is-invalid @enderror"
                                       name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Phone</label>
                                <input type="tel" id="phone" class="form-control mygroup @error('phone') is-invalid @enderror"
                                       name="phone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Mobile </label>
                                <input id="mobile" type="tel"
                                       class="mygroup form-control @error('mobile') is-invalid @enderror"
                                       name="mobile" placeholder="Enter Mobile Number"
                                       autofocus >
                                {{--                                <input type="number" class="form-control mygroup @error('mobile') is-invalid @enderror" id="mobile"--}}
                                {{--                                       name="mobile" placeholder="Mobile">--}}
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea cols="25" rows="5" class="form-control" name="address_details" placeholder="Address Details"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Alternate Contact Number</label>
                                <input type="tel" class="form-control" name="alternate_contact_number"
                                       id="alternate-contact-number" placeholder="Alternate Contact Number">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Fax</label>
                                <input type="text" class="form-control" name="fax" placeholder="fax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Preferences</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Preference ID</label>
                                <input type="text" class="form-control @error('preference_id') is-invalid @enderror"
                                       name="preference_id" placeholder="Preference ID">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Label</label>
                                <input type="text" class="form-control @error('label') is-invalid @enderror"
                                       name="label" placeholder="Label">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Email Preference </label>
                                <select class="form-control" name="email_preference" autofocus>
                                    <option value="default">Default</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Print On Check Us</label>
                                <input type="text" class="form-control" name="print_on_check_as" placeholder="Print On Check Us">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Transaction Via</label>
                                <select class="form-control" name="send_transaction_via" autofocus>
                                    <option value="email">Email</option>
                                    <option value="print">Print</option>
                                    <option value="fax">Fax</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Notes</label>
                                <input type="text" class="form-control" name="notes" placeholder="Notes">

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea cols="25" rows="5" class="form-control" name="address" placeholder="Address"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Default Shipping Address</label>
                                <textarea cols="25" rows="5" class="form-control" name="default_shipping_address" placeholder="Shipping Address"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Default Billing Address</label>
                                <textarea cols="25" rows="5" class="form-control" name="default_billing_address" placeholder="Billing Address"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center mb-3">
                <button type="submit" class="btn btn-info" >Submit</button>
            </div>
            <input id="hiddencontact" name="hiddencontact" value="{{old('hiddencontact')}}" hidden>

        </form>
    </div>

@endsection
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        var Mobile = window.intlTelInput(document.querySelector("#mobile"),
            {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "mobile",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        var Phone = window.intlTelInput(document.querySelector("#phone"),
            {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "phone",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        var AlternateContactNumber = window.intlTelInput(document.querySelector("#alternate-contact-number"),
            {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "alternate_contact_number",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        const file1InputLicense = document.querySelector("#passport-upload");
        const file2InputLicense = document.querySelector("#trade-licence-upload");
        const file3InputLicense = document.querySelector("#vat-certificate-upload");

        const previewFile1 = document.querySelector("#file1-preview");
        const previewFile2 = document.querySelector("#file2-preview");
        const previewFile3 = document.querySelector("#file3-preview");

        file1InputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile1.firstChild) {
                previewFile1.removeChild(previewFile1.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile1.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile1.appendChild(image);
            }
        });
        file2InputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile2.firstChild) {
                previewFile2.removeChild(previewFile2.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile2.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile2.appendChild(image);
            }
        });
        file3InputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile3.firstChild) {
                previewFile3.removeChild(previewFile3.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile3.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile3.appendChild(image);
            }
        });

        $("#form-create").validate({
            ignore: [],
            rules: {
                vendor_type: {
                    required: true,
                },
                category: {
                    required: true,
                },
                trade_name_or_individual_name: {
                    required: true,
                },
                Id_number: {
                    required: true,
                },
                nationality: {
                    required: function(element){
                        return $("#vendor-type").val() == "Individual";
                    }
                },
                passport_number: {
                    required: function(element){
                        return $("#vendor-type").val() == "Individual";
                    }
                },
                passport_copy_file:{
                    required: function(element){
                        return $("#vendor-type").val() == "Individual";
                    },
                    extension: "pdf|png|jpg|jpeg|svg"

                },
                trade_registration_place: {
                    required: function(element){
                        return $("#vendor-type").val() == "Company";
                    },
                },
                trade_license_number: {
                    required: function(element){
                        return $("#vendor-type").val() == "Company";
                    },
                },
                trade_license_file:{
                    required: function(element){
                        return $("#vendor-type").val() == "Company";
                    },
                    extension: "pdf|png|jpg|jpeg|svg"

                },
                vat_certificate_file:{
                    extension: "pdf|png|jpg|jpeg|svg"

                },
                address_details:{
                    required: true
                },
                email:{
                    require_from_group: [1, '.mygroup'],
                    email: true
                },
                phone:{
                    require_from_group: [1, '.mygroup'],
                    minlength:5,
                    maxlength:15,
                    number:true
                },
                mobile:{
                    require_from_group: [1, '.mygroup'],
                    minlength:5,
                    maxlength:15,
                    number:true
                },
                alternate_contact_number: {
                    minlength:5,
                    maxlength:15,
                    number:true
                },
                messages: {
                    passport_copy_file: {
                        extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
                    },
                    trade_license_file: {
                        extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
                    },
                    vat_certificate_file: {
                        extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
                    }
                }
            },
            submitHandler: function(form) {
                // This function will be called when the form is submitted and passes validation
                var trade_name_or_individual_name = $('#trade_name_or_individual_name').val();
                $.ajax({
                    url: '{{ route('vendorchecking.checkingname') }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_name_or_individual_name': trade_name_or_individual_name
                    },
                    success: function(response) {
                        if (response.exists) {
                            alert("Name Already Existing");
                        } else {
                            form.submit();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert("Name Already Existing");
                        }
                    }
                });
            }
        });
        $(document).ready(function() {
            $('#trade_name_or_individual_name').on('blur', function() {
                var trade_name_or_individual_name = $(this).val();
                $.ajax({
                    url: '{{ route('vendorchecking.checkingname') }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_name_or_individual_name': trade_name_or_individual_name
                    },
                    success: function(response) {
                        $('#trade_name_or_individual_nameError').hide().text('');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            alert("Name Already Existing");
                        }
                    }
                });
            });
        });
    </script>
@endpush

