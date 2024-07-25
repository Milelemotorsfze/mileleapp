@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
            margin-bottom: 10px;
        }
    </style>
    @can('edit-customer')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-customer');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit Customer</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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

                <form action="{{ route('dm-customers.update', $customer->id) }}" id="form-update" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name"  placeholder="Enter Name" value="{{ old('name', $customer->name) }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-control" name="country_id" id="country" autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" {{ $customer->country_id == $country->id ? 'selected' : '' }}> {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control" name="type" id="customer-type">
                                    <option value="" disabled>Type</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}" {{ $customer->type == \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}" {{ $customer->type == \App\Models\Clients::CUSTOMER_TYPE_COMPANY ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}"  {{ $customer->type == \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    
                                </select>
                            </div>
                        </div>

{{--                        <div class="col-lg-3 col-md-6">--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="choices-single-default" class="form-label">Company Name</label>--}}
{{--                                <input type="text" class="form-control" name="company_name" placeholder="Enter Company Name" value="{{ old('company_name', $customer->company_name) }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Passport File</label>
                                <input type="file" class="form-control" name="passport_file" accept='image/*' id="file1-upload"  placeholder="Upload Passport">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Trade License</label>
                                <input type="file" class="form-control" id="file2-upload" accept='image/*' name="trade_license_file" placeholder="Upload Trade License">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="5" cols="25">{{ old('address', $customer->address) }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6" id="file-preview">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                            <div id="file1-preview">
                                @if($customer->passport)
                                    <h6 class="fw-bold text-center">Passport</h6>
                                    <iframe src="{{ url('storage/app/public/passports/' . $customer->passport) }}" alt="Trade License "></iframe>

                                @endif

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                            <div id="file2-preview">
                                @if($customer->tradelicense)
                                    <h6 class="fw-bold text-center">Trade License</h6>
                                    <iframe src="{{ url('storage/app/public/tradelicenses/' . $customer->tradelicense) }}" alt="Trade License"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>


        const fileInputLicense1 = document.querySelector("#file1-upload");
        const fileInputLicense2 = document.querySelector("#file2-upload");

        const previewFile1 = document.querySelector("#file1-preview");
        const previewFile2 = document.querySelector("#file2-preview");

        fileInputLicense1.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile1.firstChild) {
                previewFile1.removeChild(previewFile1.firstChild);
            }

            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
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
            }
        });
        fileInputLicense2.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile2.firstChild) {
                previewFile2.removeChild(previewFile2.firstChild);
            }
            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
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
            }
        });

        $('#country').select2({
            placeholder: 'Select Country'
        })
        $('#country').change(function (){
            $('#country-error').remove();
        })
        $("#form-update").validate({
            rules: {
                name: {
                    required: true,
                },
                type: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                passport_file:{
                      extension: "png|jpeg|jpg"
                },
                trade_license_file:{
                     extension: "png|jpeg|jpg"
                }
            },
            messages: {
                trade_license_file: {
                    extension: "Please upload image file format (png,jpeg,jpg)"
                },
                passport_file:{
                    extension: "Please upload Image file format (png,jpeg,jpg)"
                }
            },
        });
    </script>
@endpush


