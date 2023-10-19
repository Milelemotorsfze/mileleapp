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

                <form action="{{ route('customers.store') }}" id="form-create" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name"  placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-control" name="country" id="country" autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country}}"> {{ $country }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control" name="type" id="customer-type">
                                    <option value="" disabled>Type</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}>{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}>{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}>{{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}>{{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" placeholder="Enter Company Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="5" cols="25"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6" id="file-preview">
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
                country: {
                    required: true,
                },
            }
        });
    </script>
@endpush


