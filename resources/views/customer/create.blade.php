@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
            margin-bottom: 10px;
        }
    </style>
    @can('create-customer')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-customer');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Create New Customer</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('dm-customers.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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

                <form action="{{ route('dm-customers.store') }}" id="form-create" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name')}}"  placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-control" name="country_id[]" id="country" multiple autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" @if( old('country_id') == $country->id) selected="selected" @endif  > {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control" name="type" id="customer-type">
                                    <option value="" disabled>Type</option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}
                                    @if( old('type') == 'Individual' ) selected="selected" @endif >{{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}
                                    @if( old('type') == 'Company') selected="selected" @endif >{{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}
                                    @if( old('type') == 'Government') selected="selected" @endif >{{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Passport File</label>
                                <input type="file" class="form-control" accept='image/*' name="passport_file" id="file1-upload"  placeholder="Upload Passport">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Trade License</label>
                                <input type="file" class="form-control" id="file2-upload"  accept='image/*'  name="trade_license_file" placeholder="Upload Trade License">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="5" cols="25">{{ old('address')}}</textarea>
                            </div>
                        </div>
                        <br>
                        <div class="card preview-div" hidden>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div id="file1-preview">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div id="file2-preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="col-12 text-center">
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
            $('.preview-div').attr('hidden', false);
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
            $('.preview-div').attr('hidden', false);

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
        $("#form-create").validate({
            rules: {
                name: {
                    required: true,
                },
                type: {
                    required: true,
                },
                "country_id[]": {
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


