@extends('layouts.main')
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<div class="card-header">
    <h4 class="card-title">
     Proforma Invoice
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
    <div class="row">
    <div class="col-sm-4">
        Document Details
</div> 
<div class="col-sm-4">
Client's Details
</div> 
<div class="col-sm-4">
Delivery Details
</div> 
</div>
<hr>
    <div class="row">
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Document No : 
        </div>
        <div class="col-sm-6">
        {{$callDetails->id}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        <label for="timeRange">Document Validity:</label> 
        </div>
        <div class="col-sm-6">
        <select id="timeRange">
        <option value="1">1 day</option>
        <option value="7">7 days</option>
        <option value="14">14 days</option>
        <option value="30">30 days</option>
        <option value="60">60 days</option>
    </select>
        </div>
    </div>
    @php
        $user = Auth::user();
        $empProfile = $user->empProfile;
    @endphp
    <div class="row">
        <div class="col-sm-6">
        Sales Person : 
        </div>
        <div class="col-sm-6">
        {{ Auth::user()->name }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Office : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->office }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Email ID : 
        </div>
        <div class="col-sm-6">
        {{ Auth::user()->email }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Sales Contact No : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->phone }}
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Customer ID : 
        </div>
        <div class="col-sm-6">
        {{ $empProfile->id }} 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Company : 
        </div>
        <div class="col-sm-6">
          <input type="text" name="company" id="company"> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        <label for="timeRange">Person :</label> 
        
        </div>
        <div class="col-sm-6">
        <input type="text" name="name" id="name" value="{{$callDetails->name}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Contact No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="phone_number" id="phone_number" value="{{$callDetails->phone}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Email : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="email" id="email" value="{{$callDetails->email}}">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Address : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Final Destination : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address"> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Incoterm : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Place of Delivery : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
    </div>
	<hr>
        <div class="row">
        <div class="col-sm-4">
        Payment Details
        </div> 
        <div class="col-sm-8">
        Client's Representative
        </div>  
        </div>
        <hr>
	    <div class="row">
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        System Code : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Payment Terms : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
        </div>
         </div>
        <div class="col-sm-4">
        <div class="row">
        <div class="col-sm-6">
        Rep Name : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        Rep No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="row">
        <div class="col-sm-6">
        CB Name : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
        CB No : 
        </div>
        <div class="col-sm-6">
        <input type="text" name="address" id="address">
        </div>
    </div>
        </div>
    </div>
    <hr>
                    <div class="row"> 
                    <h4>Search Available Vehicles</h4>
                    <br>
                    <br>
                    <div class="col-lg-2 col-md-6">
                    <label for="brand">Select Brand:</label>
            <select class="form-control" id="brand" name="brand">
                <option value="">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="model_line">Select Model Line:</label>
            <select class="form-control" id="model_line" name="model_line" disabled>
                <option value="">Select Model Line</option>
            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="variant">Select Variant:</label>
            <select class="form-control" id="variant" name="variant" disabled>
                <option value="">Select Variant</option>
            </select>
            </div>
            <div class="col-lg-2 col-md-6">
            <label for="interior_color">Select Interior Color:</label>
    <select class="form-control" id="interior_color" name="interior_color" disabled>
        <option value="">Select Interior Color</option>
    </select>
            </div>
            <div class="col-lg-2 col-md-6">
            <label for="exterior_color">Select Exterior Color:</label>
    <select class="form-control" id="exterior_color" name="exterior_color" disabled>
        <option value="">Select Exterior Color</option>
    </select>
            </div>
            <div class="col-lg-2 col-md-6 d-flex align-items-end">
        <button type="button" class="btn btn-primary" id="search-button">Search</button>
    </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="row">
            <div class="col-lg-12">
            <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead class="bg-soft-secondary">
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>VIN</th>
                            <th>Brand Name</th>
                            <th>Model Line</th>
                            <th>Model Details</th>
                            <th>Variant Name</th>
                            <th>Variant Detail</th>
                            <th>Interior Color</th>
                            <th>Exterior Color</th>
                            <th style="width:30px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
			</div>  
        </div>
    </div>
</div>
@endsection
@push('scripts')
@endpush