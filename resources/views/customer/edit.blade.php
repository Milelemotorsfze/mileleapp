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

                <form action="{{ route('customers.update', $customer->id) }}" id="form-update" method="POST" enctype="multipart/form-data">
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
                                <select class="form-control" name="country" id="country" autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country}}" {{ $customer->country == $country ? 'selected' : '' }}> {{ $country }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control" name="type" id="customer-type">
                                    <option value="" disabled>Type</option>
                                    <option value="{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}" {{ $customer->type == \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL ? 'selected' : '' }}>
                                        {{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value="{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}" {{ $customer->type == \App\Models\Customer::CUSTOMER_TYPE_COMPANY ? 'selected' : '' }}>
                                        {{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value="{{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}"  {{ $customer->type == \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT ? 'selected' : '' }}>
                                        {{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    <option value="{{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}"  {{ $customer->type == \App\Models\Customer::CUSTOMER_TYPE_NGO ? 'selected' : '' }}>
                                        {{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" placeholder="Enter Company Name" value="{{ old('company_name', $customer->company_name) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="5" cols="25">{{ old('address', $customer->address) }}</textarea>
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
        $("#form-update").validate({
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


